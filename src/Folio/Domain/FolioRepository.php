<?php

declare(strict_types=1);

namespace ManyData\Folio\Domain;

interface FolioRepository
{
    public function save(Folio $folio): void;

    public function find(string $id);

    public function findByFolio(string $folio);
    
    public function searchWithFilters(
        ?string $search,
        ?string $startDate,
        ?string $endDate,
        int $page,
        int $perPage
    ): array;
}
