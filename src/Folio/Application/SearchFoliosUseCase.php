<?php

declare(strict_types=1);

namespace ManyData\Folio\Application;

use ManyData\Folio\Domain\FolioRepository;

final class SearchFoliosUseCase
{
    public function __construct(
        private readonly FolioRepository $repository
    ) {}

    public function execute(
        ?string $search = null,
        ?string $startDate = null,
        ?string $endDate = null,
        int $page = 1,
        int $perPage = 20
    ): array {
        return $this->repository->searchWithFilters(
            $search,
            $startDate,
            $endDate,
            $page,
            $perPage
        );
    }
}