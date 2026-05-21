<?php

namespace App\Services;

use App\Models\Productos;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ProductoService
{
    public function search(array $filters): LengthAwarePaginator
    {
        return Productos::query()
            ->with('categoria')
            ->when($filters['with_trashed'] ?? false, fn ($query) => $query->withTrashed())
            ->when($filters['only_trashed'] ?? false, fn ($query) => $query->onlyTrashed())
            ->when($filters['categoria_id'] ?? null, fn ($query, $id) => $query->where('categoria_id', $id))
            ->when($filters['modelo_anio'] ?? null, fn ($query, $year) => $query->where('modelo_anio', $year))
            ->when($filters['min_precio'] ?? null, fn ($query, $price) => $query->where('precio_lista', '>=', $price))
            ->when($filters['max_precio'] ?? null, fn ($query, $price) => $query->where('precio_lista', '<=', $price))
            ->when($filters['q'] ?? null, fn ($query, $term) => $query->where('nombre_producto', 'like', "%{$term}%"))
            ->orderBy('nombre_producto')
            ->paginate($filters['per_page'] ?? 15);
    }
}
