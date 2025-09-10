<?php

declare(strict_types=1);

namespace ManyData\FileImport\Domain;

use ManyData\FileImport\Domain\ValueObject\FileImportProgressId;
use ManyData\FileImport\Domain\ValueObject\FileImportProgressFilePath;
use ManyData\FileImport\Domain\ValueObject\FileImportProgressType;
use ManyData\FileImport\Domain\ValueObject\FileImportProgressTotalRows;
use ManyData\FileImport\Domain\ValueObject\FileImportProgressProcessedRows;
use ManyData\FileImport\Domain\ValueObject\FileImportProgressStatus;
use ManyData\FileImport\Domain\ValueObject\FileImportProgressErrorMessage;

final class FileImportProgress
{
    private function __construct(
        private ?FileImportProgressId $id,
        private readonly FileImportProgressFilePath $filePath,
        private readonly FileImportProgressType $type,
        private FileImportProgressTotalRows $totalRows,
        private FileImportProgressProcessedRows $processedRows,
        private FileImportProgressStatus $status,
        private ?\DateTimeImmutable $startedAt,
        private ?\DateTimeImmutable $completedAt,
        private ?FileImportProgressErrorMessage $errorMessage,
        private readonly \DateTimeImmutable $createdAt,
        private \DateTimeImmutable $updatedAt
    ) {}

    public static function create(
        string $filePath,
        string $type,
        int $totalRows
    ): self {
        return new self(
            id: null,
            filePath: new FileImportProgressFilePath($filePath),
            type: new FileImportProgressType($type),
            totalRows: new FileImportProgressTotalRows($totalRows),
            processedRows: new FileImportProgressProcessedRows(0),
            status: FileImportProgressStatus::pending(),
            startedAt: null,
            completedAt: null,
            errorMessage: null,
            createdAt: new \DateTimeImmutable(),
            updatedAt: new \DateTimeImmutable()
        );
    }

    public function start(): void
    {
        $this->status = FileImportProgressStatus::processing();
        $this->startedAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function updateProgress(int $processedRows): void
    {
        $this->processedRows = new FileImportProgressProcessedRows($processedRows);
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function complete(): void
    {
        $this->status = FileImportProgressStatus::completed();
        $this->completedAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function fail(string $errorMessage): void
    {
        $this->status = FileImportProgressStatus::failed();
        $this->errorMessage = new FileImportProgressErrorMessage($errorMessage);
        $this->completedAt = new \DateTimeImmutable();
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function id(): ?FileImportProgressId
    {
        return $this->id;
    }
    
    public function setId(FileImportProgressId $id): void
    {
        $this->id = $id;
    }

    public function filePath(): FileImportProgressFilePath
    {
        return $this->filePath;
    }

    public function type(): FileImportProgressType
    {
        return $this->type;
    }

    public function totalRows(): FileImportProgressTotalRows
    {
        return $this->totalRows;
    }

    public function processedRows(): FileImportProgressProcessedRows
    {
        return $this->processedRows;
    }

    public function status(): FileImportProgressStatus
    {
        return $this->status;
    }

    public function startedAt(): ?\DateTimeImmutable
    {
        return $this->startedAt;
    }

    public function completedAt(): ?\DateTimeImmutable
    {
        return $this->completedAt;
    }

    public function errorMessage(): ?FileImportProgressErrorMessage
    {
        return $this->errorMessage;
    }

    public function createdAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function updatedAt(): \DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function progressPercentage(): int
    {
        $total = $this->totalRows->value();
        if ($total === 0) {
            return 0;
        }
        
        return (int) round(($this->processedRows->value() / $total) * 100);
    }

    public static function fromPrimitives(array $data): self
    {
        return new self(
            id: new FileImportProgressId($data['id']),
            filePath: new FileImportProgressFilePath($data['file_path']),
            type: new FileImportProgressType($data['type']),
            totalRows: new FileImportProgressTotalRows($data['total_rows']),
            processedRows: new FileImportProgressProcessedRows($data['processed_rows']),
            status: new FileImportProgressStatus($data['status']),
            startedAt: $data['started_at'] ? new \DateTimeImmutable($data['started_at']) : null,
            completedAt: $data['completed_at'] ? new \DateTimeImmutable($data['completed_at']) : null,
            errorMessage: $data['error_message'] ? new FileImportProgressErrorMessage($data['error_message']) : null,
            createdAt: new \DateTimeImmutable($data['created_at']),
            updatedAt: new \DateTimeImmutable($data['updated_at'])
        );
    }

    public function toPrimitives(): array
    {
        return [
            'id' => $this->id?->value(),
            'file_path' => $this->filePath->value(),
            'type' => $this->type->value(),
            'total_rows' => $this->totalRows->value(),
            'processed_rows' => $this->processedRows->value(),
            'status' => $this->status->value(),
            'started_at' => $this->startedAt?->format('Y-m-d H:i:s'),
            'completed_at' => $this->completedAt?->format('Y-m-d H:i:s'),
            'error_message' => $this->errorMessage?->value(),
            'created_at' => $this->createdAt->format('Y-m-d H:i:s'),
            'updated_at' => $this->updatedAt->format('Y-m-d H:i:s')
        ];
    }
}