<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\V1\Concerns\RespondsWithJson;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\CategoriaRequest;
use App\Http\Resources\Api\V1\CategoriaResource;
use App\Models\Categorias;
use App\Services\CategoriaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CategoriaController extends Controller
{
    use RespondsWithJson;

    public function __construct(private readonly CategoriaService $categorias)
    {
    }

    public function index(Request $request): JsonResponse
    {
        return $this->success(
            CategoriaResource::collection($this->categorias->search($request->query())),
            'Categorias obtenidas correctamente.'
        );
    }

    public function store(CategoriaRequest $request): JsonResponse
    {
        Gate::authorize('gestionar-categorias');

        return $this->success(
            new CategoriaResource(Categorias::create($request->validated())),
            'Categoria creada correctamente.',
            201
        );
    }

    public function show(Categorias $categoria): JsonResponse
    {
        return $this->success(new CategoriaResource($categoria->loadCount('productos')), 'Categoria obtenida correctamente.');
    }

    public function update(CategoriaRequest $request, Categorias $categoria): JsonResponse
    {
        Gate::authorize('gestionar-categorias');

        $categoria->update($request->validated());

        return $this->success(new CategoriaResource($categoria->refresh()), 'Categoria actualizada correctamente.');
    }

    public function destroy(Categorias $categoria): JsonResponse
    {
        Gate::authorize('gestionar-categorias');

        $categoria->delete();

        return $this->deleted('Categoria eliminada correctamente.');
    }

    public function restore(int $categoria): JsonResponse
    {
        Gate::authorize('gestionar-categorias');

        $model = Categorias::onlyTrashed()->findOrFail($categoria);
        $model->restore();

        return $this->success(new CategoriaResource($model), 'Categoria restaurada correctamente.');
    }
}
