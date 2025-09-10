<?php

declare(strict_types=1);

namespace ManyData\FileImport\Domain\ValueObject;

final class FileImportProgressTotalRows
{
    public function __construct(private readonly int $value)
    {
        $this->ensureIsValidValue($value);
    }

    private function ensureIsValidValue(int $value): void
    {
        if ($value < 0) {
            throw new \InvalidArgumentException('Total rows cannot be negative');
        }
    }

    public function value(): int
    {
        return $this->value;
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }

    public function __toString(): string
    {
        return (string) $this->value;
    }
}