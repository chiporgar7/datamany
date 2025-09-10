<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SearchFoliosRequest;
use ManyData\Folio\Application\SearchFoliosUseCase;
use Illuminate\Http\JsonResponse;

class SearchFoliosController extends Controller
{
    public function __construct(
        private readonly SearchFoliosUseCase $searchFoliosUseCase
    ) {}

    public function __invoke(SearchFoliosRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $results = $this->searchFoliosUseCase->execute(
            $validated['search'] ?? null,
            $validated['start_date'] ?? null,
            $validated['end_date'] ?? null,
            (int) ($validated['page'] ?? 1),
            (int) ($validated['per_page'] ?? 20)
        );

        return response()->json($results);
    }
}