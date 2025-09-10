<?php

declare(strict_types=1);

namespace ManyData\FileImport\Domain;

use ManyData\FileImport\Domain\ValueObject\ImportedFileId;

interface ImportedFileRepository
{
    public function save(ImportedFile $file): void;

    public function find(ImportedFileId $id): ?ImportedFile;

    public function delete(ImportedFileId $id): void;
}