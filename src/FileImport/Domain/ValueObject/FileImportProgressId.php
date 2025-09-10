<?php

declare(strict_types=1);

namespace ManyData\FileImport\Domain\ValueObject;

final class FileImportProgressId
{
    public function __construct(private readonly int $value)
    {
        $this->ensureIsValidId($value);
    }

    private function ensureIsValidId(int $value): void
    {
        if ($value < 1) {
            throw new \InvalidArgumentException('The ID must be greater than 0');
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