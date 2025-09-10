<?php

namespace ManyData\TransactionData\Infrastructure;

use ManyData\TransactionData\Domain\TransactionData;
use ManyData\TransactionData\Domain\TransactionDataRepository;
use Illuminate\Support\Facades\DB;

final class TransactionDataRepositoryEloquent implements TransactionDataRepository
{
    public function save(TransactionData $transactionData): void
    {
        $data = $transactionData->toPrimitives();
        unset($data['id']);
        DB::table('transaction_data')->insert($data);
    }

    public function findById(string $id): ?TransactionData
    {
        return null;
    }

    public function findAll(): array
    {
        return [];
    }

    public function findByTransactionId(string $transactionId): ?TransactionData
    {
        return null;
    }

    public function findByUserId(string $userId): array
    {
        return [];
    }
    
    public function searchWithFilters(
        ?string $startDate,
        ?string $endDate,
        ?int $userId,
        int $page,
        int $perPage
    ): array {
        $query = DB::table('transaction_data');
        
        if ($startDate !== null) {
            $query->where('created_at', '>=', $startDate);
        }
        
        if ($endDate !== null) {
            $query->where('created_at', '<=', $endDate);
        }
        
        if ($userId !== null) {
            $query->where('user_id', $userId);
        }
        
        $total = $query->count();
        
        $results = $query
            ->orderBy('created_at', 'desc')
            ->offset(($page - 1) * $perPage)
            ->limit($perPage)
            ->get();
        
        return [
            'data' => $results->toArray(),
            'meta' => [
                'current_page' => $page,
                'per_page' => $perPage,
                'total' => $total,
                'last_page' => (int) ceil($total / $perPage)
            ]
        ];
    }
    
    public function getTotalMoneyMetrics(): array {
        $result = DB::table('transaction_data')
            ->selectRaw('
                COUNT(*) as total_transactions,
                SUM(transaction_value) as total_money,
                AVG(transaction_value) as average_transaction,
                MIN(transaction_value) as min_transaction,
                MAX(transaction_value) as max_transaction
            ')
            ->first();
            
        return [
            'total_transactions' => $result->total_transactions ?? 0,
            'total_money' => $result->total_money ?? 0,
            'average_transaction' => $result->average_transaction ?? 0,
            'min_transaction' => $result->min_transaction ?? 0,
            'max_transaction' => $result->max_transaction ?? 0
        ];
    }
    
    public function getTopUsersByMoney(int $limit): array {
        $results = DB::table('transaction_data')
            ->select('user_id')
            ->selectRaw('
                SUM(transaction_value) as total_money,
                COUNT(*) as transaction_count,
                AVG(transaction_value) as average_transaction
            ')
            ->groupBy('user_id')
            ->orderByDesc('total_money')
            ->limit($limit)
            ->get();
            
        return $results->map(function ($user) {
            return [
                'user_id' => $user->user_id,
                'total_money' => $user->total_money,
                'transaction_count' => $user->transaction_count,
                'average_transaction' => $user->average_transaction
            ];
        })->toArray();
    }
}
