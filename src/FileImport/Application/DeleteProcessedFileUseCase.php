<?php

declare(strict_types=1);

namespace ManyData\FileImport\Application;

use Illuminate\Support\Facades\Storage;
use ManyData\FileImport\Domain\FileImportProgressRepository;
use ManyData\FileImport\Domain\Exception\FileImportException;
use ManyData\FileImport\Domain\ValueObject\FileImportProgressId;

final class DeleteProcessedFileUseCase
{
    public function __construct(
        private readonly FileImportProgressRepository $repository
    ) {}

    public function execute(string $progressId): void
    {
        $progress = $this->repository->find(new FileImportProgressId((int) $progressId));
        
        if ($progress === null) {
            throw FileImportException::notFound($progressId);
        }

        if (!$progress->isCompleted()) {
            throw FileImportException::cannotDeleteIncompleteImport($progressId);
        }

        $filePath = str_replace('imports/', '', $progress->filePath());
        
        if (Storage::disk('local')->exists('imports/' . $filePath)) {
            Storage::disk('local')->delete('imports/' . $filePath);
        }
    }
}