<?php

declare(strict_types=1);

namespace ManyData\TransactionData\Application;

use Illuminate\Http\UploadedFile;
use ManyData\FileImport\Application\SaveFileUseCase;

final class ImportTransactionsUseCase
{
    public function __construct(
        private readonly SaveFileUseCase $saveFileUseCase
    ) {}

    public function execute(UploadedFile $file): string
    {
        return $this->saveFileUseCase->execute($file);
    }
}
