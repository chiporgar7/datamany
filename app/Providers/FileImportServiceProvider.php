<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use ManyData\FileImport\Domain\ImportedFileRepository;
use ManyData\FileImport\Infrastructure\ImportedFileRepositoryInMemory;
use ManyData\FileImport\Application\SaveFileUseCase;
use ManyData\Folio\Application\ImportFoliosUseCase;
use ManyData\Folio\Domain\FolioRepository;
use ManyData\Folio\Infrastructure\FolioRepositoryEloquent;
use ManyData\TransactionData\Application\ImportTransactionsUseCase;
use ManyData\TransactionData\Domain\TransactionDataRepository;
use ManyData\TransactionData\Infrastructure\TransactionDataRepositoryEloquent;
use ManyData\FileImport\Domain\FileImportProgressRepository;
use ManyData\FileImport\Infrastructure\FileImportProgressRepositoryEloquent;
use ManyData\FileImport\Application\DispatchFileImportUseCase;
use ManyData\FileImport\Application\UpdateFileImportProgressUseCase;
use ManyData\Folio\Application\CreateFolioUseCase;
use ManyData\TransactionData\Application\CreateTransactionDataUseCase;

class FileImportServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(
            ImportedFileRepository::class,
            ImportedFileRepositoryInMemory::class
        );

        $this->app->bind(
            FolioRepository::class,
            FolioRepositoryEloquent::class
        );

        $this->app->bind(
            TransactionDataRepository::class,
            TransactionDataRepositoryEloquent::class
        );

        $this->app->bind(
            FileImportProgressRepository::class,
            FileImportProgressRepositoryEloquent::class
        );

        $this->app->bind(SaveFileUseCase::class, function ($app) {
            return new SaveFileUseCase(
                $app->make(ImportedFileRepository::class)
            );
        });

        $this->app->bind(ImportFoliosUseCase::class, function ($app) {
            return new ImportFoliosUseCase(
                $app->make(SaveFileUseCase::class)
            );
        });

        $this->app->bind(ImportTransactionsUseCase::class, function ($app) {
            return new ImportTransactionsUseCase(
                $app->make(SaveFileUseCase::class)
            );
        });

        $this->app->bind(DispatchFileImportUseCase::class, function ($app) {
            return new DispatchFileImportUseCase(
                $app->make(FileImportProgressRepository::class)
            );
        });

        $this->app->bind(UpdateFileImportProgressUseCase::class, function ($app) {
            return new UpdateFileImportProgressUseCase(
                $app->make(FileImportProgressRepository::class)
            );
        });

        $this->app->bind(CreateFolioUseCase::class, function ($app) {
            return new CreateFolioUseCase(
                $app->make(FolioRepository::class)
            );
        });

        $this->app->bind(CreateTransactionDataUseCase::class, function ($app) {
            return new CreateTransactionDataUseCase(
                $app->make(TransactionDataRepository::class)
            );
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}