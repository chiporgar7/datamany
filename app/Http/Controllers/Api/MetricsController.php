<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TopUsersMetricsRequest;
use ManyData\TransactionData\Application\GetTotalMoneyMetricUseCase;
use ManyData\TransactionData\Application\GetTopUsersMetricUseCase;
use Illuminate\Http\JsonResponse;

class MetricsController extends Controller
{
    public function __construct(
        private readonly GetTotalMoneyMetricUseCase $getTotalMoneyMetricUseCase,
        private readonly GetTopUsersMetricUseCase $getTopUsersMetricUseCase
    ) {}

    public function totalMoney(): JsonResponse
    {
        $metrics = $this->getTotalMoneyMetricUseCase->execute();
        
        return response()->json([
            'data' => $metrics
        ]);
    }
    
    public function topUsers(TopUsersMetricsRequest $request): JsonResponse
    {
        $validated = $request->validated();
        
        $users = $this->getTopUsersMetricUseCase->execute(
            (int) ($validated['limit'] ?? 10)
        );
        
        return response()->json([
            'data' => $users
        ]);
    }
}