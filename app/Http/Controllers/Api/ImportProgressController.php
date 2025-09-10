<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use ManyData\FileImport\Application\GetImportProgressUseCase;
use Illuminate\Http\JsonResponse;

class ImportProgressController extends Controller
{
    public function __construct(
        private readonly GetImportProgressUseCase $getImportProgressUseCase
    ) {}

    public function show(int $progressId): JsonResponse
    {
        try {
            $progress = $this->getImportProgressUseCase->execute($progressId);
            
            return response()->json([
                'data' => $progress
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Progress not found'
            ], 404);
        }
    }
}