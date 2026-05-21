<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Database\Factories\Items_PedidosFactory;

class Items_Pedidos extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'items_pedido';
    
    public $timestamps = false;

    protected $fillable = [
        'pedido_id',
        'item_id',
        'producto_id',
        'cantidad',
        'precio_lista',
        'descuento',
    ];

    protected $casts = [
        'pedido_id' => 'integer',
        'item_id' => 'integer',
        'producto_id' => 'integer',
        'cantidad' => 'integer',
        'precio_lista' => 'decimal:2',
        'descuento' => 'decimal:2',
    ];

    protected static function newFactory(): Items_PedidosFactory
    {
        return Items_PedidosFactory::new();
    }

    public function pedido(): BelongsTo
    {
        return $this->belongsTo(Pedido::class, 'pedido_id');
    }

    public function producto(): BelongsTo
    {
        return $this->belongsTo(Productos::class, 'producto_id');
    }
}
