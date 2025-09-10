<?php

namespace App\Http\Controllers\ImportData;

use App\Http\Controllers\Controller;
use App\Http\Requests\ImportData\ImportFoliosRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use ManyData\Folio\Application\ImportFoliosUseCase;
use ManyData\FileImport\Application\DispatchFileImportUseCase;

class ImportFoliosController extends Controller
{
    public function __construct(
        private readonly ImportFoliosUseCase $importFoliosUseCase,
        private readonly DispatchFileImportUseCase $dispatchFileImportUseCase
    ) {}

    public function __invoke(ImportFoliosRequest $request): JsonResponse
    {
        return DB::transaction(function () use ($request) {
            $filePath = $this->importFoliosUseCase->execute($request->file('file'));

            $progressId = $this->dispatchFileImportUseCase->execute($filePath, 'folio');

            return response()->json([
                'message' => 'File uploaded successfully',
                'path' => $filePath,
                'progressId' => $progressId
            ]);
        });
    }
}
