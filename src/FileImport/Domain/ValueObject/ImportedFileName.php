<?php

declare(strict_types=1);

namespace ManyData\FileImport\Domain\ValueObject;

final class ImportedFileName
{
    public function __construct(private readonly string $value)
    {
        $this->ensureIsNotEmpty($value);
        $this->ensureIsValidFileName($value);
    }

    private function ensureIsNotEmpty(string $value): void
    {
        if (empty(trim($value))) {
            throw new \InvalidArgumentException('File name cannot be empty');
        }
    }

    private function ensureIsValidFileName(string $value): void
    {
        // Basic validation to prevent path traversal
        if (preg_match('/\.\./', $value)) {
            throw new \InvalidArgumentException('Invalid file name');
        }
    }

    public function value(): string
    {
        return $this->value;
    }

    public function extension(): string
    {
        return pathinfo($this->value, PATHINFO_EXTENSION);
    }

    public function withoutExtension(): string
    {
        return pathinfo($this->value, PATHINFO_FILENAME);
    }

    public function __toString(): string
    {
        return $this->value;
    }
}