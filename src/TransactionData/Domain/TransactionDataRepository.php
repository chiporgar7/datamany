<?php

declare(strict_types=1);

namespace ManyData\TransactionData\Domain;

interface TransactionDataRepository
{
    public function save(TransactionData $transaction): void;
    
    public function findById(string $id): ?TransactionData;
    
    public function findAll(): array;
    
    public function findByTransactionId(string $transactionId): ?TransactionData;
    
    public function findByUserId(string $userId): array;
    
    public function searchWithFilters(
        ?string $startDate,
        ?string $endDate,
        ?int $userId,
        int $page,
        int $perPage
    ): array;
    
    public function getTotalMoneyMetrics(): array;
    
    public function getTopUsersByMoney(int $limit): array;
}