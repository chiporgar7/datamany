<?php

declare(strict_types=1);

namespace ManyData\TransactionData\Application;

use ManyData\TransactionData\Domain\TransactionDataRepository;

final class GetTopUsersMetricUseCase
{
    public function __construct(
        private readonly TransactionDataRepository $repository
    ) {}

    public function execute(int $limit = 10): array {
        return $this->repository->getTopUsersByMoney($limit);
    }
}