<?php

declare(strict_types=1);

namespace ManyData\FileImport\Domain\ValueObject;

final class ImportedFileSize
{
    public function __construct(private readonly int $value)
    {
        $this->ensureIsValidSize($value);
    }

    private function ensureIsValidSize(int $value): void
    {
        if ($value < 0) {
            throw new \InvalidArgumentException('File size cannot be negative');
        }
    }

    public function value(): int
    {
        return $this->value;
    }

    public function inKilobytes(): float
    {
        return $this->value / 1024;
    }

    public function inMegabytes(): float
    {
        return $this->value / 1048576;
    }

    public function humanReadable(): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = $this->value;
        $unit = 0;

        while ($bytes >= 1024 && $unit < count($units) - 1) {
            $bytes /= 1024;
            $unit++;
        }

        return sprintf('%.2f %s', $bytes, $units[$unit]);
    }

    public function __toString(): string
    {
        return (string) $this->value;
    }
}