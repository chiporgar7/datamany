<?php

declare(strict_types=1);

namespace ManyData\FileImport\Domain\ValueObject;

final class ImportedFilePath
{
    public function __construct(private readonly string $value)
    {
        $this->ensureIsNotEmpty($value);
        $this->ensureIsValidPath($value);
    }

    private function ensureIsNotEmpty(string $value): void
    {
        if (empty(trim($value))) {
            throw new \InvalidArgumentException('File path cannot be empty');
        }
    }

    private function ensureIsValidPath(string $value): void
    {
        // Ensure path starts with imports/ to maintain consistency
        if (!str_starts_with($value, 'imports/')) {
            throw new \InvalidArgumentException('File path must start with "imports/"');
        }
    }

    public function value(): string
    {
        return $this->value;
    }

    public function directory(): string
    {
        return dirname($this->value);
    }

    public function filename(): string
    {
        return basename($this->value);
    }

    public function __toString(): string
    {
        return $this->value;
    }
}