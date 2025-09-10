<?php

declare(strict_types=1);

namespace ManyData\TransactionData\Application;

use ManyData\TransactionData\Domain\TransactionDataRepository;

final class SearchTransactionsUseCase
{
    public function __construct(
        private readonly TransactionDataRepository $repository
    ) {}

    public function execute(
        ?string $startDate = null,
        ?string $endDate = null,
        ?int $userId = null,
        int $page = 1,
        int $perPage = 20
    ): array {
        return $this->repository->searchWithFilters(
            $startDate,
            $endDate,
            $userId,
            $page,
            $perPage
        );
    }
}