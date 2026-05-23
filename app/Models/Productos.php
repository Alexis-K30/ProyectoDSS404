<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Productos extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'productos';

    protected $fillable = [
        'nombre_producto',
        'marca_id',
        'categoria_id',
        'modelo_anio',
        'precio_lista',
        'imagenes',
    ];

    protected $casts = [
        'marca_id'     => 'integer',
        'categoria_id' => 'integer',
        'modelo_anio'  => 'integer',
        'precio_lista' => 'decimal:2',
        'imagenes'     => 'array',
    ];

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categorias::class, 'categoria_id');
    }

    public function itemsPedido(): HasMany
    {
        return $this->hasMany(Items_Pedidos::class, 'producto_id');
    }

    public function pedidos(): BelongsToMany
    {
        return $this->belongsToMany(Pedido::class, 'items_pedido', 'producto_id', 'pedido_id')
            ->withPivot('item_id', 'cantidad', 'precio_lista', 'descuento');
    }
}