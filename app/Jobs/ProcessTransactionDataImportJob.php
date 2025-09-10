<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use ManyData\TransactionData\Application\CreateTransactionDataUseCase;
use ManyData\FileImport\Application\UpdateProgressUseCase;
use League\Csv\Reader;
use Illuminate\Support\Facades\Storage;

class ProcessTransactionDataImportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private const CHUNK_SIZE = 1000;

    public function __construct(
        private readonly string $filePath,
        private readonly int $progressId
    ) {}

    public function handle(
        CreateTransactionDataUseCase $createTransactionDataUseCase,
        UpdateProgressUseCase $updateProgressUseCase
    ): void {
        $csv = Reader::createFromPath(storage_path($this->filePath));
        $csv->setHeaderOffset(0);
        
        $totalRecords = $csv->count();
        $processedRecords = 0;
        
        foreach ($csv->getRecordsChunked(self::CHUNK_SIZE) as $chunk) {
            foreach ($chunk as $row) {
                try {
                    $createTransactionDataUseCase->execute(
                        (int) ($row['transaction_id'] ?? 0),
                        (int) ($row['user_id'] ?? 0),
                        (float) ($row['transaction_value'] ?? 0)
                    );
                    
                    $processedRecords++;
                } catch (\Exception $e) {
                    \Log::error('Error processing transaction data row: ' . $e->getMessage());
                }
            }
            
            $progress = ($processedRecords / $totalRecords) * 100;
            $updateProgressUseCase->execute($this->progressId, $processedRecords, (int) $progress);
        }
        
        $updateProgressUseCase->execute($this->progressId, $totalRecords, 100, 'completed');
        
        Storage::delete($this->filePath);
    }
}