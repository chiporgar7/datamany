<?php

namespace App\Http\Controllers\ImportData;

use App\Http\Controllers\Controller;
use App\Http\Requests\ImportData\ImportTransactionsRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use ManyData\TransactionData\Application\ImportTransactionsUseCase;
use ManyData\FileImport\Application\DispatchFileImportUseCase;

class ImportTransactionsController extends Controller
{
    public function __construct(
        private readonly ImportTransactionsUseCase $importTransactionsUseCase,
        private readonly DispatchFileImportUseCase $dispatchFileImportUseCase
    ) {}

    public function __invoke(ImportTransactionsRequest $request): JsonResponse
    {
        return DB::transaction(function () use ($request) {
            $filePath = $this->importTransactionsUseCase->execute($request->file('file'));
            $progressId = $this->dispatchFileImportUseCase->execute($filePath, 'transaction');

            return response()->json([
                'message' => 'File uploaded successfully',
                'path' => $filePath,
                'progressId' => $progressId
            ]);
        });
    }
}
