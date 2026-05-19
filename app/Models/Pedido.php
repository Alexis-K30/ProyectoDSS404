<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pedido extends Model
{
    use HasFactory;

    protected $table = 'pedidos';

    protected $fillable = [
        'usuario_id', 'estado_pedido', 'fecha_pedido',
        'fecha_requerida', 'fecha_envio', 'tienda_id', 'personal_id',
    ];

    protected $casts = [
        'fecha_pedido'    => 'date',
        'fecha_requerida' => 'date',
        'fecha_envio'     => 'date',
        'estado_pedido'   => 'integer',
    ];

    const ESTADOS = [
        1 => 'Pendiente',
        2 => 'Procesando',
        3 => 'Rechazado',
        4 => 'Completado',
    ];

    protected function etiquetaEstado(): Attribute
    {
        return Attribute::make(
            get: fn () => self::ESTADOS[$this->estado_pedido] ?? 'Desconocido'
        );
    }

    protected function total(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->items->sum(
                fn ($i) => $i->cantidad * $i->precio_lista * (1 - $i->descuento / 100)
            )
        );
    }

    public function scopePendientes(Builder $q): Builder  { return $q->where('estado_pedido', 1); }
    public function scopeCompletados(Builder $q): Builder { return $q->where('estado_pedido', 4); }
    public function scopeDelMes(Builder $q): Builder
    {
        return $q->whereMonth('fecha_pedido', now()->month)
                 ->whereYear('fecha_pedido', now()->year);
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'usuario_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(Items_Pedidos::class, 'pedido_id');
    }

    public function productos(): BelongsToMany
    {
        return $this->belongsToMany(Productos::class, 'items_pedido', 'pedido_id', 'producto_id')
                    ->withPivot('item_id', 'cantidad', 'precio_lista', 'descuento');
    }
}
