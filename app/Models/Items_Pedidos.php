<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Items_Pedidos extends Model
{
    protected $table = 'items_pedido';
    
    public $timestamps = false;
    public $incrementing = false;

    protected $fillable = [
        'pedido_id',
        'item_id',
        'producto_id',
        'cantidad',
        'precio_lista',
        'descuento',
    ];

    public function pedido(): BelongsTo
    {
        return $this->belongsTo(Pedido::class, 'pedido_id');
    }

    public function producto(): BelongsTo
    {
        return $this->belongsTo(Productos::class, 'producto_id');
    }
}
