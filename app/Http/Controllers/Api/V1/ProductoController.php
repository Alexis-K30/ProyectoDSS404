<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\V1\Concerns\RespondsWithJson;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\ProductoRequest;
use App\Http\Resources\Api\V1\ProductoResource;
use App\Models\Productos;
use App\Services\ProductoService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ProductoController extends Controller
{
    use RespondsWithJson;

    public function __construct(private readonly ProductoService $productos)
    {
    }

    public function index(Request $request): JsonResponse
    {
        return $this->success(
            ProductoResource::collection($this->productos->search($request->query())),
            'Productos obtenidos correctamente.'
        );
    }

    public function store(ProductoRequest $request): JsonResponse
    {
        Gate::authorize('gestionar-productos');

        return $this->success(
            new ProductoResource(Productos::create($request->validated())->load('categoria')),
            'Producto creado correctamente.',
            201
        );
    }

    public function show(Productos $producto): JsonResponse
    {
        return $this->success(new ProductoResource($producto->load('categoria')), 'Producto obtenido correctamente.');
    }

    public function update(ProductoRequest $request, Productos $producto): JsonResponse
    {
        Gate::authorize('gestionar-productos');

        $producto->update($request->validated());

        return $this->success(new ProductoResource($producto->refresh()->load('categoria')), 'Producto actualizado correctamente.');
    }

    public function destroy(Productos $producto): JsonResponse
    {
        Gate::authorize('gestionar-productos');

        $producto->delete();

        return $this->deleted('Producto eliminado correctamente.');
    }

    public function restore(int $producto): JsonResponse
    {
        Gate::authorize('gestionar-productos');

        $model = Productos::onlyTrashed()->findOrFail($producto);
        $model->restore();

        return $this->success(new ProductoResource($model->load('categoria')), 'Producto restaurado correctamente.');
    }
}
