<?php

declare(strict_types=1);

namespace ManyData\FileImport\Domain\Exception;

final class FileStorageException extends FileImportException
{
    public static function unableToStore(string $filename, string $reason): self
    {
        return new self(sprintf(
            'Unable to store file "%s": %s',
            $filename,
            $reason
        ));
    }
}