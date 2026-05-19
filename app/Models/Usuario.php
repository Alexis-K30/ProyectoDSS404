<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Usuario extends Authenticatable
{
    use HasFactory;

    protected $table = 'usuarios';

    protected $fillable = [
        'nombre', 'apellido', 'email', 'password',
        'telefono', 'calle', 'ciudad', 'estado_dir', 'codigo_postal',
        'rol', 'estado',
    ];

    protected $hidden = ['password'];

    protected $casts = [
        'email_verificado_en' => 'datetime',
        'rol'    => 'integer',
        'estado' => 'integer',
    ];

    // --- Accessors ---
    protected function nombreCompleto(): Attribute
    {
        return Attribute::make(
            get: fn () => "{$this->nombre} {$this->apellido}"
        );
    }

    protected function direccionFormateada(): Attribute
    {
        return Attribute::make(
            get: fn () => implode(', ', array_filter([
                $this->calle, $this->ciudad,
                $this->estado_dir, $this->codigo_postal,
            ]))
        );
    }

    protected function esAdmin(): Attribute
    {
        return Attribute::make(get: fn () => $this->rol === 3);
    }

    // --- Scopes ---
    public function scopeActivos(Builder $q): Builder
    {
        return $q->where('estado', 1);
    }

    public function scopeClientes(Builder $q): Builder
    {
        return $q->where('rol', 1);
    }

    public function scopeAdmins(Builder $q): Builder
    {
        return $q->where('rol', 3);
    }

    // --- Control de cuenta ---
    public function isAccountNonExpired(): bool     { return $this->estado !== 2; }
    public function isAccountNonLocked(): bool      { return $this->estado !== 0; }
    public function isCredentialsNonExpired(): bool { return true; }
    public function isEnabled(): bool               { return $this->estado === 1; }

    // --- Relaciones ---
    public function pedidos(): HasMany
    {
        return $this->hasMany(Pedido::class, 'usuario_id');
    }
}
