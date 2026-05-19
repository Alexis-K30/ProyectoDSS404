<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Productos extends Model
{
    use HasFactory;

    protected $table = 'productos';

    protected $fillable = [
        'nombre_producto',
        'marca_id',
        'categoria_id',
        'modelo_anio',
        'precio_lista',
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
