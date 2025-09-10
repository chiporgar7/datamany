<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SearchTransactionsRequest;
use ManyData\TransactionData\Application\SearchTransactionsUseCase;
use Illuminate\Http\JsonResponse;

class SearchTransactionsController extends Controller
{
    public function __construct(
        private readonly SearchTransactionsUseCase $searchTransactionsUseCase
    ) {}

    public function __invoke(SearchTransactionsRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $results = $this->searchTransactionsUseCase->execute(
            $validated['start_date'] ?? null,
            $validated['end_date'] ?? null,
            isset($validated['user_id']) ? (int) $validated['user_id'] : null,
            (int) ($validated['page'] ?? 1),
            (int) ($validated['per_page'] ?? 20)
        );

        return response()->json($results);
    }
}