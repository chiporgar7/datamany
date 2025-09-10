<?php

declare(strict_types=1);

namespace ManyData\FileImport\Application;

use Illuminate\Http\UploadedFile;
use ManyData\FileImport\Domain\ImportedFile;
use ManyData\FileImport\Domain\ImportedFileRepository;
use ManyData\FileImport\Domain\ValueObject\ImportedFileId;
use ManyData\FileImport\Domain\Exception\FileStorageException;

final class SaveFileUseCase
{
    public function __construct(
        private readonly ImportedFileRepository $repository
    ) {}

    public function execute(UploadedFile $file): string
    {

        $this->validateFile($file);

        $fileId = ImportedFileId::generate();

        $extension = $file->getClientOriginalExtension();
        $storedFileName = sprintf(
            '%s_%s.%s',
            date('Y-m-d_His'),
            $fileId->value(),
            $extension
        );

        $storagePath = 'imports/' . $storedFileName;
        try {
            $path = $file->storeAs('imports', $storedFileName, 'local');

            if ($path === false) {
                throw FileStorageException::unableToStore(
                    $file->getClientOriginalName(),
                    'Failed to store file in filesystem'
                );
            }
            
            \Log::info('File stored', [
                'path' => $path,
                'storage_path' => $storagePath,
                'full_path' => storage_path('app/' . $storagePath),
                'exists' => file_exists(storage_path('app/' . $storagePath))
            ]);

            $importedFile = ImportedFile::create(
                id: $fileId->value(),
                originalName: $file->getClientOriginalName(),
                storedPath: $storagePath,
                size: $file->getSize(),
                mimeType: $file->getMimeType() ?? $file->getClientMimeType()
            );

            $this->repository->save($importedFile);

            return $storagePath;

        } catch (\Exception $e) {
            // If storage fails, clean up any partially stored file
            if (isset($path) && \Storage::exists($path)) {
                \Storage::delete($path);
            }

            if ($e instanceof FileStorageException) {
                throw $e;
            }

            throw FileStorageException::unableToStore(
                $file->getClientOriginalName(),
                $e->getMessage()
            );
        }
    }


    private function validateFile(UploadedFile $file): void
    {
        if (!$file->isValid()) {
            throw FileStorageException::unableToStore(
                $file->getClientOriginalName(),
                'File upload failed or is corrupted'
            );
        }
    }
}
