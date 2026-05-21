<?php

namespace App\Services;

use App\Models\Pedido;
use App\Models\Productos;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class PedidoService
{
    public function search(array $filters): LengthAwarePaginator
    {
        return Pedido::query()
            ->with(['usuario', 'items.producto'])
            ->when($filters['with_trashed'] ?? false, fn ($query) => $query->withTrashed())
            ->when($filters['only_trashed'] ?? false, fn ($query) => $query->onlyTrashed())
            ->when($filters['usuario_id'] ?? null, fn ($query, $id) => $query->where('usuario_id', $id))
            ->when($filters['estado_pedido'] ?? null, fn ($query, $status) => $query->where('estado_pedido', $status))
            ->when($filters['desde'] ?? null, fn ($query, $date) => $query->whereDate('fecha_pedido', '>=', $date))
            ->when($filters['hasta'] ?? null, fn ($query, $date) => $query->whereDate('fecha_pedido', '<=', $date))
            ->latest()
            ->paginate($filters['per_page'] ?? 15);
    }

    public function create(array $data): Pedido
    {
        return DB::transaction(function () use ($data): Pedido {
            $items = $data['items'] ?? [];
            unset($data['items']);

            $pedido = Pedido::create($data);
            $this->syncItems($pedido, $items);

            return $pedido->load(['usuario', 'items.producto']);
        });
    }

    public function update(Pedido $pedido, array $data): Pedido
    {
        return DB::transaction(function () use ($pedido, $data): Pedido {
            $items = $data['items'] ?? null;
            unset($data['items']);

            $pedido->update($data);

            if (is_array($items)) {
                $pedido->items()->forceDelete();
                $this->syncItems($pedido, $items);
            }

            return $pedido->refresh()->load(['usuario', 'items.producto']);
        });
    }

    private function syncItems(Pedido $pedido, array $items): void
    {
        foreach (array_values($items) as $index => $item) {
            $producto = Productos::findOrFail($item['producto_id']);

            $pedido->items()->create([
                'item_id' => $index + 1,
                'producto_id' => $producto->id,
                'cantidad' => $item['cantidad'],
                'precio_lista' => $item['precio_lista'] ?? $producto->precio_lista,
                'descuento' => $item['descuento'] ?? 0,
            ]);
        }
    }
}
