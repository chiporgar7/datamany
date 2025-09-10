<?php

declare(strict_types=1);

namespace ManyData\FileImport\Domain\ValueObject;

final class FileImportProgressStatus
{
    private const PENDING = 'pending';
    private const PROCESSING = 'processing';
    private const COMPLETED = 'completed';
    private const FAILED = 'failed';
    private const VALID_STATUSES = [self::PENDING, self::PROCESSING, self::COMPLETED, self::FAILED];

    public function __construct(private readonly string $value)
    {
        $this->ensureIsValidStatus($value);
    }

    private function ensureIsValidStatus(string $value): void
    {
        if (!in_array($value, self::VALID_STATUSES, true)) {
            throw new \InvalidArgumentException(
                sprintf('Invalid status "%s". Valid statuses are: %s', $value, implode(', ', self::VALID_STATUSES))
            );
        }
    }

    public function value(): string
    {
        return $this->value;
    }

    public static function pending(): self
    {
        return new self(self::PENDING);
    }

    public static function processing(): self
    {
        return new self(self::PROCESSING);
    }

    public static function completed(): self
    {
        return new self(self::COMPLETED);
    }

    public static function failed(): self
    {
        return new self(self::FAILED);
    }

    public function isPending(): bool
    {
        return $this->value === self::PENDING;
    }

    public function isProcessing(): bool
    {
        return $this->value === self::PROCESSING;
    }

    public function isCompleted(): bool
    {
        return $this->value === self::COMPLETED;
    }

    public function isFailed(): bool
    {
        return $this->value === self::FAILED;
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