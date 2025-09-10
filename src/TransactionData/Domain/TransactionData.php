<?php

declare(strict_types=1);

namespace ManyData\TransactionData\Domain;

final class TransactionData
{
    public function __construct(
        private readonly string $id,
        private readonly int $transactionId,
        private readonly int $userId,
        private readonly float $transactionValue,
        private readonly \DateTimeImmutable $createdAt,
        private readonly \DateTimeImmutable $updatedAt
    ) {}

    public static function create(
        string $id,
        int $transactionId,
        int $userId,
        float $transactionValue
    ): self {
        $now = new \DateTimeImmutable();
        return new self(
            $id,
            $transactionId,
            $userId,
            $transactionValue,
            $now,
            $now
        );
    }

    public function id(): string
    {
        return $this->id;
    }

    public function transactionId(): int
    {
        return $this->transactionId;
    }

    public function userId(): int
    {
        return $this->userId;
    }

    public function transactionValue(): float
    {
        return $this->transactionValue;
    }

    public function createdAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function updatedAt(): \DateTimeImmutable
    {
        return $this->updatedAt;
    }
    
    public function toPrimitives(): array
    {
        return [
            'id' => $this->id,
            'transaction_id' => $this->transactionId,
            'user_id' => $this->userId,
            'transaction_value' => $this->transactionValue,
            'created_at' => $this->createdAt->format('Y-m-d H:i:s'),
            'updated_at' => $this->updatedAt->format('Y-m-d H:i:s')
        ];
    }
}