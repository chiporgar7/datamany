<?php

declare(strict_types=1);

namespace ManyData\FileImport\Application;

use ManyData\FileImport\Domain\FileImportProgressRepository;
use ManyData\FileImport\Domain\ValueObject\FileImportProgressId;

final class GetImportProgressUseCase
{
    public function __construct(
        private readonly FileImportProgressRepository $repository
    ) {}

    public function execute(int $progressId): array
    {
        $progress = $this->repository->find(new FileImportProgressId($progressId));
        
        if (!$progress) {
            throw new \Exception('Progress not found');
        }
        
        return [
            'id' => $progress->id()->value(),
            'file_path' => $progress->filePath()->value(),
            'type' => $progress->type()->value(),
            'total_rows' => $progress->totalRows()->value(),
            'processed_rows' => $progress->processedRows()->value(),
            'status' => $progress->status()->value(),
            'progress_percentage' => $progress->progressPercentage(),
            'started_at' => $progress->startedAt()?->format('Y-m-d H:i:s'),
            'completed_at' => $progress->completedAt()?->format('Y-m-d H:i:s'),
            'error_message' => $progress->errorMessage()?->value()
        ];
    }
}