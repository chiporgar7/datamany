<?php

declare(strict_types=1);

namespace ManyData\FileImport\Application;

use ManyData\FileImport\Domain\FileImportProgressRepository;
use ManyData\FileImport\Domain\ValueObject\FileImportProgressId;

final class UpdateFileImportProgressUseCase
{
    public function __construct(
        private readonly FileImportProgressRepository $repository
    ) {}

    public function updateProgress(int $progressId, int $processedRows): void
    {
        $progress = $this->repository->find(new FileImportProgressId($progressId));
        
        if (!$progress) {
            throw new \RuntimeException("Progress not found with id: {$progressId}");
        }

        $progress->updateProgress($processedRows);
        $this->repository->update($progress);
    }

    public function markAsCompleted(int $progressId): void
    {
        $progress = $this->repository->find(new FileImportProgressId($progressId));
        
        if (!$progress) {
            throw new \RuntimeException("Progress not found with id: {$progressId}");
        }

        $progress->complete();
        $this->repository->update($progress);
    }

    public function markAsFailed(int $progressId, string $errorMessage): void
    {
        $progress = $this->repository->find(new FileImportProgressId($progressId));
        
        if (!$progress) {
            throw new \RuntimeException("Progress not found with id: {$progressId}");
        }

        $progress->fail($errorMessage);
        $this->repository->update($progress);
    }
}