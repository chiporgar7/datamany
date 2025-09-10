<?php

declare(strict_types=1);

namespace ManyData\FileImport\Infrastructure;

use ManyData\FileImport\Domain\FileImportProgress;
use ManyData\FileImport\Domain\FileImportProgressRepository;
use ManyData\FileImport\Domain\ValueObject\FileImportProgressId;
use App\Models\FileImportProgress as EloquentFileImportProgress;

final class FileImportProgressRepositoryEloquent implements FileImportProgressRepository
{
    public function save(FileImportProgress $progress): void
    {
        $primitives = $progress->toPrimitives();
        unset($primitives['id']);
        
        $eloquentProgress = new EloquentFileImportProgress();
        $eloquentProgress->fill($primitives);
        $eloquentProgress->save();
        
        $progress->setId(new FileImportProgressId($eloquentProgress->id));
    }
    
    public function find(FileImportProgressId $id): ?FileImportProgress
    {
        $eloquentProgress = EloquentFileImportProgress::find($id->value());
        
        if (!$eloquentProgress) {
            return null;
        }
        
        return FileImportProgress::fromPrimitives($eloquentProgress->toArray());
    }
    
    public function update(FileImportProgress $progress): void
    {
        if ($progress->id() === null) {
            throw new \RuntimeException('Cannot update FileImportProgress without an ID');
        }
        
        $eloquentProgress = EloquentFileImportProgress::find($progress->id()->value());
        
        if (!$eloquentProgress) {
            throw new \RuntimeException('FileImportProgress not found with id: ' . $progress->id()->value());
        }
        
        $eloquentProgress->fill($progress->toPrimitives());
        $eloquentProgress->save();
    }
}