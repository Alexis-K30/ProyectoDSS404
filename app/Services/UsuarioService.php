<?php

namespace App\Services;

use App\Models\Usuario;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;

class UsuarioService
{
    public function search(array $filters): LengthAwarePaginator
    {
        return Usuario::query()
            ->when($filters['with_trashed'] ?? false, fn ($query) => $query->withTrashed())
            ->when($filters['only_trashed'] ?? false, fn ($query) => $query->onlyTrashed())
            ->when($filters['rol'] ?? null, fn ($query, $rol) => $query->where('rol', $rol))
            ->when(isset($filters['estado']), fn ($query) => $query->where('estado', $filters['estado']))
            ->when($filters['q'] ?? null, function ($query, string $term): void {
                $query->where(function ($query) use ($term): void {
                    $query->where('nombre', 'like', "%{$term}%")
                        ->orWhere('apellido', 'like', "%{$term}%")
                        ->orWhere('email', 'like', "%{$term}%");
                });
            })
            ->latest()
            ->paginate($filters['per_page'] ?? 15);
    }

    public function create(array $data): Usuario
    {
        $data['password'] = Hash::make($data['password']);

        return Usuario::create($data);
    }

    public function update(Usuario $usuario, array $data): Usuario
    {
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $usuario->update($data);

        return $usuario->refresh();
    }
}
