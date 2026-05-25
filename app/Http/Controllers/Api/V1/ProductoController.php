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
use Illuminate\Support\Facades\Storage;
 
class ProductoController extends Controller
{
    use RespondsWithJson;
 
    public function __construct(private readonly ProductoService $productos)
    {
    }
 
    public function index(Request $request): JsonResponse
    {
        $paginator = $this->productos->search($request->query());
 
        return $this->success(
            ProductoResource::collection($paginator)->resolve(),
            'Productos obtenidos correctamente.',
            200,
            [
                'current_page' => $paginator->currentPage(),
                'last_page'    => $paginator->lastPage(),
                'per_page'     => $paginator->perPage(),
                'total'        => $paginator->total(),
            ]
        );
    }
 
    public function store(ProductoRequest $request): JsonResponse
    {
        Gate::authorize('gestionar-productos');
 
        $data = $request->validated();
        $data['imagenes'] = $this->subirImagenes($request);
        $data['stock'] = $request->input('stock', 0);

 
        return $this->success(
            new ProductoResource(Productos::create($data)->load('categoria')),
            'Producto creado correctamente.',
            201
        );
    }
 
    public function show(Productos $producto): JsonResponse
    {
        return $this->success(
            new ProductoResource($producto->load('categoria')),
            'Producto obtenido correctamente.'
        );
    }
 
    public function update(ProductoRequest $request, Productos $producto): JsonResponse
    {
        Gate::authorize('gestionar-productos');
 
        $data = $request->validated();
 
        // Si se suben nuevas imágenes, eliminar las anteriores y subir las nuevas
        if ($request->hasFile('imagenes')) {
            $this->eliminarImagenes($producto->imagenes ?? []);
            $data['imagenes'] = $this->subirImagenes($request);
        }

        // Actualizar stock si viene en la petición
        if ($request->has('stock')) {
            $data['stock'] = $request->input('stock');
        }
    
        $producto->update($data);
 
        return $this->success(
            new ProductoResource($producto->refresh()->load('categoria')),
            'Producto actualizado correctamente.'
        );
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
 
        return $this->success(
            new ProductoResource($model->load('categoria')),
            'Producto restaurado correctamente.'
        );
    }
 
    // -------------------------------------------------------
    // Helpers privados
    // -------------------------------------------------------
    private function subirImagenes(Request $request): array
    {
        $urls = [];
 
        if ($request->hasFile('imagenes')) {
            foreach ($request->file('imagenes') as $imagen) {
                $path = $imagen->store('uploads', 'public');
                $urls[] = asset('storage/' . $path);
            }
        }
 
        return $urls;
    }
 
    private function eliminarImagenes(array $urls): void
    {
        foreach ($urls as $url) {
            $path = str_replace(asset('storage/'), '', $url);
            Storage::disk('public')->delete($path);
        }
    }
}
