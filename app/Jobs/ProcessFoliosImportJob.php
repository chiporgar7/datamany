<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use ManyData\FileImport\Domain\ValueObject\FileImportProgressId;
use App\Events\ImportProgressUpdated;
use App\Events\ImportCompleted;
use ManyData\FileImport\Infrastructure\FileImportProgressRepositoryEloquent;
use ManyData\Folio\Application\CreateFolioUseCase;
// use ManyData\FileImport\Application\DeleteProcessedFileUseCase;

class ProcessFoliosImportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private const CHUNK_SIZE = 1000;

    public function __construct(
        private readonly int $progressId,
        private readonly string $filePath
    ) {}

    public function handle(): void
    {
        $progressRepository = new FileImportProgressRepositoryEloquent();
        $progress = $progressRepository->find(new FileImportProgressId($this->progressId));

        if (!$progress) {
            throw new \RuntimeException('Progress not found');
        }

        try {
            $progress->start();
            $progressRepository->update($progress);

            $fullPath = storage_path('app/' . $this->filePath);

            $file = new \SplFileObject($fullPath);
            $file->setFlags(\SplFileObject::READ_CSV);

            $header = $file->fgetcsv();

            $processedRows = 0;
            $batch = [];

            while (!$file->eof()) {
                $row = $file->fgetcsv();
                if ($row && count($row) > 1) {
                    $mappedRow = array_combine($header, $row);

                    $batch[] = $mappedRow;

                    if (count($batch) >= self::CHUNK_SIZE) {
                        $this->processBatch($batch);
                        $processedRows += count($batch);
                        $progress->updateProgress($processedRows);
                        $progressRepository->update($progress);
                        
                        // Emitir evento de progreso
                        event(new ImportProgressUpdated(
                            $this->progressId,
                            'processing',
                            $processedRows,
                            $progress->totalRows()->value(),
                            $progress->progressPercentage()
                        ));
                        
                        $batch = [];
                    }
                }
            }

            if (!empty($batch)) {
                $this->processBatch($batch);
                $processedRows += count($batch);
                $progress->updateProgress($processedRows);
                $progressRepository->update($progress);
            }

            $progress->complete();
            $progressRepository->update($progress);
            
            // Emitir evento de completado
            event(new ImportCompleted(
                $this->progressId,
                'folios',
                true,
                $processedRows
            ));

            // Eliminar archivo después de procesar
            Storage::delete($this->filePath);

        } catch (\Exception $e) {
            $progress->fail($e->getMessage());
            $progressRepository->update($progress);
            
            // Emitir evento de error
            event(new ImportCompleted(
                $this->progressId,
                'folios',
                false,
                $processedRows ?? 0,
                $e->getMessage()
            ));
            
            throw $e;
        }
    }

    private function processBatch(array $batch): void
    {
        $createFolioUseCase = app(CreateFolioUseCase::class);

        foreach ($batch as $row) {
            $createFolioUseCase->execute(
                $row['name'] ?? '',
                (float) ($row['amount'] ?? 0),
                $row['folio'] ?? '',
                $row['fecha_transaccion'] ?? '',
                $row['extra_field1'] ?? '',
                $row['extra_field2'] ?? ''
            );
        }
    }
}
