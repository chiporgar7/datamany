<?php

declare(strict_types=1);

namespace ManyData\FileImport\Domain;

use ManyData\FileImport\Domain\ValueObject\ImportedFileId;
use ManyData\FileImport\Domain\ValueObject\ImportedFileName;
use ManyData\FileImport\Domain\ValueObject\ImportedFilePath;
use ManyData\FileImport\Domain\ValueObject\ImportedFileSize;
use ManyData\FileImport\Domain\ValueObject\ImportedFileMimeType;

final class ImportedFile
{
    private function __construct(
        private readonly ImportedFileId $id,
        private readonly ImportedFileName $originalName,
        private readonly ImportedFilePath $storedPath,
        private readonly ImportedFileSize $size,
        private readonly ImportedFileMimeType $mimeType,
        private readonly \DateTimeImmutable $uploadedAt
    ) {}

    public static function create(
        string $id,
        string $originalName,
        string $storedPath,
        int $size,
        string $mimeType
    ): self {
        return new self(
            id: new ImportedFileId($id),
            originalName: new ImportedFileName($originalName),
            storedPath: new ImportedFilePath($storedPath),
            size: new ImportedFileSize($size),
            mimeType: new ImportedFileMimeType($mimeType),
            uploadedAt: new \DateTimeImmutable()
        );
    }

    public function id(): ImportedFileId
    {
        return $this->id;
    }

    public function originalName(): ImportedFileName
    {
        return $this->originalName;
    }

    public function storedPath(): ImportedFilePath
    {
        return $this->storedPath;
    }

    public function size(): ImportedFileSize
    {
        return $this->size;
    }

    public function mimeType(): ImportedFileMimeType
    {
        return $this->mimeType;
    }

    public function uploadedAt(): \DateTimeImmutable
    {
        return $this->uploadedAt;
    }

    public static function fromPrimitives(array $data): self
    {
        return new self(
            id: new ImportedFileId($data['id']),
            originalName: new ImportedFileName($data['originalName']),
            storedPath: new ImportedFilePath($data['storedPath']),
            size: new ImportedFileSize($data['size']),
            mimeType: new ImportedFileMimeType($data['mimeType']),
            uploadedAt: new \DateTimeImmutable($data['uploadedAt'])
        );
    }

    public function toPrimitives(): array
    {
        return [
            'id' => $this->id->value(),
            'originalName' => $this->originalName->value(),
            'storedPath' => $this->storedPath->value(),
            'size' => $this->size->value(),
            'mimeType' => $this->mimeType->value(),
            'uploadedAt' => $this->uploadedAt->format('Y-m-d H:i:s')
        ];
    }
}