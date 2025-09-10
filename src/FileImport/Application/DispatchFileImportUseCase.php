<?php

declare(strict_types=1);

namespace ManyData\FileImport\Application;

use ManyData\FileImport\Domain\FileImportProgress;
use ManyData\FileImport\Domain\FileImportProgressRepository;
use App\Jobs\ProcessFoliosImportJob;
use App\Jobs\ProcessTransactionsImportJob;

final class DispatchFileImportUseCase
{
    public function __construct(
        private readonly FileImportProgressRepository $repository
    ) {}

    public function execute(string $filePath, string $type): int
    {
        $fullPath = storage_path('app/' . $filePath);


        if (!file_exists($fullPath)) {
            throw new \RuntimeException("File not found: {$fullPath}");
        }

        $totalRows = $this->countCsvRows($fullPath) - 1;

        $progress = FileImportProgress::create(
            filePath: $filePath,
            type: $type,
            totalRows: $totalRows
        );

        $this->repository->save($progress);

        $job = match ($type) {
            'folio' => new ProcessFoliosImportJob($progress->id()->value(), $filePath),
            'transaction' => new ProcessTransactionsImportJob($progress->id()->value(), $filePath),
            default => throw new \InvalidArgumentException("Invalid import type: {$type}")
        };

        dispatch($job);

        return $progress->id()->value();
    }

    private function countCsvRows(string $filePath): int
    {
        $lineCount = 0;
        $file = new \SplFileObject($filePath, 'r');
        $file->setFlags(\SplFileObject::READ_CSV);

        while (!$file->eof()) {
            if ($file->fgetcsv()) {
                $lineCount++;
            }
        }

        return $lineCount;
    }
}
