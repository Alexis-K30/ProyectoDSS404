<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\V1\Concerns\RespondsWithJson;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\PedidoRequest;
use App\Http\Resources\Api\V1\PedidoResource;
use App\Models\Pedido;
use App\Services\PedidoService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PedidoController extends Controller
{
    use RespondsWithJson;

    public function __construct(private readonly PedidoService $pedidos)
    {
    }

    public function index(Request $request): JsonResponse
    {
        $filters = $request->query();

        if (!$request->user()->can('ver-todos-pedidos')) {
            $filters['usuario_id'] = $request->user()->id;
        }

        return $this->success(
            PedidoResource::collection($this->pedidos->search($filters)),
            'Pedidos obtenidos correctamente.'
        );
    }

    public function store(PedidoRequest $request): JsonResponse
    {
        $data = $request->validated();

        if (!$request->user()->can('ver-todos-pedidos')) {
            $data['usuario_id'] = $request->user()->id;
        }

        return $this->success(
            new PedidoResource($this->pedidos->create($data)),
            'Pedido creado correctamente.',
            201
        );
    }

    public function show(Pedido $pedido): JsonResponse
    {
        Gate::authorize('gestionar-pedido', $pedido);

        return $this->success(
            new PedidoResource($pedido->load(['usuario', 'items.producto'])),
            'Pedido obtenido correctamente.'
        );
    }

    public function update(PedidoRequest $request, Pedido $pedido): JsonResponse
    {
        Gate::authorize('gestionar-pedido', $pedido);

        return $this->success(
            new PedidoResource($this->pedidos->update($pedido, $request->validated())),
            'Pedido actualizado correctamente.'
        );
    }

    public function destroy(Pedido $pedido): JsonResponse
    {
        Gate::authorize('gestionar-pedido', $pedido);

        $pedido->delete();

        return $this->deleted('Pedido eliminado correctamente.');
    }

    public function restore(int $pedido): JsonResponse
    {
        $model = Pedido::onlyTrashed()->findOrFail($pedido);
        Gate::authorize('gestionar-pedido', $model);

        $model->restore();

        return $this->success(new PedidoResource($model->load(['usuario', 'items.producto'])), 'Pedido restaurado correctamente.');
    }
}
