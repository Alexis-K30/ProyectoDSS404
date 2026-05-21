<?php

namespace App\Services;

use App\Models\Categorias;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CategoriaService
{
    public function search(array $filters): LengthAwarePaginator
    {
        return Categorias::query()
            ->withCount('productos')
            ->when($filters['with_trashed'] ?? false, fn ($query) => $query->withTrashed())
            ->when($filters['only_trashed'] ?? false, fn ($query) => $query->onlyTrashed())
            ->when($filters['q'] ?? null, fn ($query, $term) => $query->where('nombre_categoria', 'like', "%{$term}%"))
            ->orderBy('nombre_categoria')
            ->paginate($filters['per_page'] ?? 15);
    }
}
