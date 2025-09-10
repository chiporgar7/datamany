<?php

declare(strict_types=1);

namespace ManyData\TransactionData\Application;

use ManyData\TransactionData\Domain\TransactionData;
use ManyData\TransactionData\Domain\TransactionDataRepository;
use Illuminate\Support\Str;

final class CreateTransactionDataUseCase
{
    public function __construct(
        private readonly TransactionDataRepository $repository
    ) {}

    public function execute(
        int $transactionId,
        int $userId,
        float $transactionValue
    ): void {
        $transaction = TransactionData::create(
            id: (string) Str::uuid(),
            transactionId: $transactionId,
            userId: $userId,
            transactionValue: $transactionValue
        );

        $this->repository->save($transaction);
    }
}
