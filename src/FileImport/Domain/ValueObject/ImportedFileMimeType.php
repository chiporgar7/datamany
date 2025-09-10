<?php

declare(strict_types=1);

namespace ManyData\FileImport\Domain\ValueObject;

final class ImportedFileMimeType
{
    private const ALLOWED_MIME_TYPES = [
        'text/csv',
        'text/plain',
        'application/csv'
    ];

    public function __construct(private readonly string $value)
    {
        $this->ensureIsValidMimeType($value);
    }

    private function ensureIsValidMimeType(string $value): void
    {
        if (!in_array($value, self::ALLOWED_MIME_TYPES, true)) {
            throw new \InvalidArgumentException(sprintf(
                'Invalid mime type "%s". Allowed types: %s',
                $value,
                implode(', ', self::ALLOWED_MIME_TYPES)
            ));
        }
    }

    public function value(): string
    {
        return $this->value;
    }

    public function isSpreadsheet(): bool
    {
        return in_array($this->value, [
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        ], true);
    }

    public function isCsv(): bool
    {
        return $this->value === 'text/csv';
    }

    public function isXml(): bool
    {
        return in_array($this->value, ['application/xml', 'text/xml'], true);
    }

    public function isJson(): bool
    {
        return $this->value === 'application/json';
    }

    public function __toString(): string
    {
        return $this->value;
    }
}