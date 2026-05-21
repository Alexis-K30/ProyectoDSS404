<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\V1\Concerns\RespondsWithJson;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\ItemPedidoRequest;
use App\Http\Resources\Api\V1\ItemPedidoResource;
use App\Models\Items_Pedidos;
use App\Models\Pedido;
use App\Services\ItemPedidoService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ItemPedidoController extends Controller
{
    use RespondsWithJson;

    public function __construct(private readonly ItemPedidoService $items)
    {
    }

    public function index(Request $request): JsonResponse
    {
        if (!$request->user()->can('ver-todos-pedidos')) {
            if (!$request->filled('pedido_id')) {
                abort(403, 'Indica un pedido propio para consultar sus items.');
            }

            Gate::authorize('gestionar-pedido', Pedido::findOrFail($request->integer('pedido_id')));
        }

        return $this->success(
            ItemPedidoResource::collection($this->items->search($request->query())),
            'Items de pedido obtenidos correctamente.'
        );
    }

    public function store(ItemPedidoRequest $request): JsonResponse
    {
        $data = $request->validated();
        Gate::authorize('gestionar-pedido', Pedido::findOrFail($data['pedido_id']));

        $item = $this->items->create($data);

        return $this->success(new ItemPedidoResource($item), 'Item de pedido creado correctamente.', 201);
    }

    public function show(Items_Pedidos $itemPedido): JsonResponse
    {
        Gate::authorize('gestionar-pedido', $itemPedido->pedido);

        return $this->success(new ItemPedidoResource($itemPedido->load('producto')), 'Item de pedido obtenido correctamente.');
    }

    public function update(ItemPedidoRequest $request, Items_Pedidos $itemPedido): JsonResponse
    {
        Gate::authorize('gestionar-pedido', $itemPedido->pedido);

        return $this->success(
            new ItemPedidoResource($this->items->update($itemPedido, $request->validated())),
            'Item de pedido actualizado correctamente.'
        );
    }

    public function destroy(Items_Pedidos $itemPedido): JsonResponse
    {
        Gate::authorize('gestionar-pedido', $itemPedido->pedido);

        $itemPedido->delete();

        return $this->deleted('Item de pedido eliminado correctamente.');
    }

    public function restore(int $itemPedido): JsonResponse
    {
        $model = Items_Pedidos::onlyTrashed()->findOrFail($itemPedido);
        Gate::authorize('gestionar-pedido', $model->pedido);

        $model->restore();

        return $this->success(new ItemPedidoResource($model->load('producto')), 'Item de pedido restaurado correctamente.');
    }
}
