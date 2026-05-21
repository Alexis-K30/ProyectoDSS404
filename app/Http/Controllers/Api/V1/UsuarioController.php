<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\V1\Concerns\RespondsWithJson;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\UsuarioRequest;
use App\Http\Resources\Api\V1\UsuarioResource;
use App\Models\Usuario;
use App\Services\UsuarioService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class UsuarioController extends Controller
{
    use RespondsWithJson;

    public function __construct(private readonly UsuarioService $usuarios)
    {
    }

    public function index(Request $request): JsonResponse
    {
        Gate::authorize('gestionar-usuarios');

        return $this->success(
            UsuarioResource::collection($this->usuarios->search($request->query())),
            'Usuarios obtenidos correctamente.'
        );
    }

    public function store(UsuarioRequest $request): JsonResponse
    {
        Gate::authorize('gestionar-usuarios');

        return $this->success(
            new UsuarioResource($this->usuarios->create($request->validated())),
            'Usuario creado correctamente.',
            201
        );
    }

    public function show(Usuario $usuario): JsonResponse
    {
        Gate::authorize('gestionar-usuarios');

        return $this->success(new UsuarioResource($usuario), 'Usuario obtenido correctamente.');
    }

    public function update(UsuarioRequest $request, Usuario $usuario): JsonResponse
    {
        Gate::authorize('gestionar-usuarios');

        return $this->success(
            new UsuarioResource($this->usuarios->update($usuario, $request->validated())),
            'Usuario actualizado correctamente.'
        );
    }

    public function destroy(Usuario $usuario): JsonResponse
    {
        Gate::authorize('gestionar-usuarios');

        $usuario->delete();

        return $this->deleted('Usuario eliminado correctamente.');
    }

    public function restore(int $usuario): JsonResponse
    {
        Gate::authorize('gestionar-usuarios');

        $model = Usuario::onlyTrashed()->findOrFail($usuario);
        $model->restore();

        return $this->success(new UsuarioResource($model), 'Usuario restaurado correctamente.');
    }
}
