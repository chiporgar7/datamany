<?php

declare(strict_types=1);

namespace ManyData\FileImport\Infrastructure;

use ManyData\FileImport\Domain\ImportedFile;
use ManyData\FileImport\Domain\ImportedFileRepository;
use ManyData\FileImport\Domain\ValueObject\ImportedFileId;

/**
 * In-memory implementation of ImportedFileRepository.
 * This can be replaced with an Eloquent implementation later.
 */
final class ImportedFileRepositoryInMemory implements ImportedFileRepository
{
    /** @var array<string, array> */
    private array $files = [];

    public function save(ImportedFile $file): void
    {
        $this->files[$file->id()->value()] = $file->toPrimitives();
    }

    public function find(ImportedFileId $id): ?ImportedFile
    {
        if (!isset($this->files[$id->value()])) {
            return null;
        }

        return ImportedFile::fromPrimitives($this->files[$id->value()]);
    }

    public function delete(ImportedFileId $id): void
    {
        unset($this->files[$id->value()]);
    }

    /**
     * Get all files (for testing purposes)
     * 
     * @return ImportedFile[]
     */
    public function all(): array
    {
        return array_map(
            fn(array $data) => ImportedFile::fromPrimitives($data),
            array_values($this->files)
        );
    }

    /**
     * Clear all files (for testing purposes)
     */
    public function clear(): void
    {
        $this->files = [];
    }
}