<?php

namespace App\Services;

use App\Models\Items_Pedidos;
use App\Models\Productos;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ItemPedidoService
{
    public function search(array $filters): LengthAwarePaginator
    {
        return Items_Pedidos::query()
            ->with(['pedido', 'producto'])
            ->when($filters['with_trashed'] ?? false, fn ($query) => $query->withTrashed())
            ->when($filters['only_trashed'] ?? false, fn ($query) => $query->onlyTrashed())
            ->when($filters['pedido_id'] ?? null, fn ($query, $id) => $query->where('pedido_id', $id))
            ->when($filters['producto_id'] ?? null, fn ($query, $id) => $query->where('producto_id', $id))
            ->orderBy('pedido_id')
            ->orderBy('item_id')
            ->paginate($filters['per_page'] ?? 15);
    }

    public function create(array $data): Items_Pedidos
    {
        $data['item_id'] ??= (Items_Pedidos::withTrashed()->where('pedido_id', $data['pedido_id'])->max('item_id') ?? 0) + 1;
        $data = $this->fillPrice($data);

        return Items_Pedidos::create($data)->load('producto');
    }

    public function update(Items_Pedidos $item, array $data): Items_Pedidos
    {
        $item->update($this->fillPrice($data));

        return $item->refresh()->load('producto');
    }

    private function fillPrice(array $data): array
    {
        if (isset($data['producto_id']) && !isset($data['precio_lista'])) {
            $data['precio_lista'] = Productos::findOrFail($data['producto_id'])->precio_lista;
        }

        $data['descuento'] ??= 0;

        return $data;
    }
}
