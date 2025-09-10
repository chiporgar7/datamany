<?php

namespace ManyData\Folio\Infrastructure;

use ManyData\Folio\Domain\Folio;
use ManyData\Folio\Domain\FolioRepository;
use Illuminate\Support\Facades\DB;

final class FolioRepositoryEloquent implements FolioRepository
{
    public function save(Folio $folio): void
    {
        $data = $folio->toPrimitives();
        unset($data['id']);
        DB::table('folios')->insert($data);
    }

    public function find(string $id)
    {
        return null;
    }

    public function findByFolio(string $folio)
    {
        return null;
    }
    
    public function searchWithFilters(
        ?string $search,
        ?string $startDate,
        ?string $endDate,
        int $page,
        int $perPage
    ): array {
        $query = DB::table('folios');
        
        if ($search !== null) {
            $query->where(function ($q) use ($search) {
                $q->where('folio', 'LIKE', "%{$search}%")
                  ->orWhere('name', 'LIKE', "%{$search}%");
            });
        }
        
        if ($startDate !== null) {
            $query->where('fecha_transaccion', '>=', $startDate);
        }
        
        if ($endDate !== null) {
            $query->where('fecha_transaccion', '<=', $endDate);
        }
        
        $total = $query->count();
        
        $results = $query
            ->orderBy('created_at', 'desc')
            ->offset(($page - 1) * $perPage)
            ->limit($perPage)
            ->get();
        
        return [
            'data' => $results->toArray(),
            'meta' => [
                'current_page' => $page,
                'per_page' => $perPage,
                'total' => $total,
                'last_page' => (int) ceil($total / $perPage)
            ]
        ];
    }
}
