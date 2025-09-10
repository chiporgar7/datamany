<?php

declare(strict_types=1);

namespace ManyData\FileImport\Domain\ValueObject;

final class FileImportProgressErrorMessage
{
    public function __construct(private readonly string $value)
    {
        $this->ensureIsNotEmpty($value);
    }

    private function ensureIsNotEmpty(string $value): void
    {
        if (empty(trim($value))) {
            throw new \InvalidArgumentException('Error message cannot be empty');
        }
    }

    public function value(): string
    {
        return $this->value;
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}