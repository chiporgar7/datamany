<?php

declare(strict_types=1);

namespace ManyData\FileImport\Domain\Exception;

class FileImportException extends \Exception
{
    public static function notFound(string $id): self
    {
        return new self(sprintf('File import progress with ID "%s" not found', $id));
    }

    public static function cannotDeleteIncompleteImport(string $id): self
    {
        return new self(sprintf('Cannot delete file for incomplete import with ID "%s"', $id));
    }
}