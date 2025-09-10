<?php

declare(strict_types=1);

namespace ManyData\FileImport\Domain;

use ManyData\FileImport\Domain\ValueObject\FileImportProgressId;

interface FileImportProgressRepository
{
    public function save(FileImportProgress $progress): void;
    
    public function find(FileImportProgressId $id): ?FileImportProgress;
    
    public function update(FileImportProgress $progress): void;
}