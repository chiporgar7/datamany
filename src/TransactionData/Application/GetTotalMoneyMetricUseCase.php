<?php

declare(strict_types=1);

namespace ManyData\TransactionData\Application;

use ManyData\TransactionData\Domain\TransactionDataRepository;

final class GetTotalMoneyMetricUseCase
{
    public function __construct(
        private readonly TransactionDataRepository $repository
    ) {}

    public function execute(): array {
        return $this->repository->getTotalMoneyMetrics();
    }
}