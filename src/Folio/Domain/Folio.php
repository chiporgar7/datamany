<?php

declare(strict_types=1);

namespace ManyData\Folio\Domain;

final class Folio
{
    public function __construct(
        private readonly string $id,
        private readonly string $name,
        private readonly float $amount,
        private readonly string $folio,
        private readonly string $fechaTransaccion,
        private readonly ?string $extraField1,
        private readonly ?string $extraField2,
        private readonly \DateTimeImmutable $createdAt,
        private readonly \DateTimeImmutable $updatedAt
    ) {}

    public static function create(
        string $id,
        string $name,
        float $amount,
        string $folio,
        string $fechaTransaccion,
        ?string $extraField1 = null,
        ?string $extraField2 = null
    ): self {
        $now = new \DateTimeImmutable();
        return new self(
            $id,
            $name,
            $amount,
            $folio,
            $fechaTransaccion,
            $extraField1,
            $extraField2,
            $now,
            $now
        );
    }

    public function id(): string
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function amount(): float
    {
        return $this->amount;
    }

    public function folio(): string
    {
        return $this->folio;
    }

    public function fechaTransaccion(): string
    {
        return $this->fechaTransaccion;
    }

    public function extraField1(): ?string
    {
        return $this->extraField1;
    }

    public function extraField2(): ?string
    {
        return $this->extraField2;
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
            'name' => $this->name,
            'amount' => $this->amount,
            'folio' => $this->folio,
            'fecha_transaccion' => $this->fechaTransaccion === '' ? null : $this->fechaTransaccion,
            'extra_field1' => $this->extraField1,
            'extra_field2' => $this->extraField2,
            'created_at' => $this->createdAt->format('Y-m-d H:i:s'),
            'updated_at' => $this->updatedAt->format('Y-m-d H:i:s')
        ];
    }
}
