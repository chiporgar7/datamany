<?php

declare(strict_types=1);

namespace ManyData\FileImport\Domain\ValueObject;

final class FileImportProgressType
{
    private const FOLIO = 'folio';
    private const TRANSACTION = 'transaction';
    private const VALID_TYPES = [self::FOLIO, self::TRANSACTION];

    public function __construct(private readonly string $value)
    {
        $this->ensureIsValidType($value);
    }

    private function ensureIsValidType(string $value): void
    {
        if (!in_array($value, self::VALID_TYPES, true)) {
            throw new \InvalidArgumentException(
                sprintf('Invalid type "%s". Valid types are: %s', $value, implode(', ', self::VALID_TYPES))
            );
        }
    }

    public function value(): string
    {
        return $this->value;
    }

    public static function folio(): self
    {
        return new self(self::FOLIO);
    }

    public static function transaction(): self
    {
        return new self(self::TRANSACTION);
    }

    public function isFolio(): bool
    {
        return $this->value === self::FOLIO;
    }

    public function isTransaction(): bool
    {
        return $this->value === self::TRANSACTION;
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