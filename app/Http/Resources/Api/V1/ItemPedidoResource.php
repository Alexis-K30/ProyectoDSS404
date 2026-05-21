<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ItemPedidoResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'pedido_id' => $this->pedido_id,
            'item_id' => $this->item_id,
            'producto_id' => $this->producto_id,
            'producto' => new ProductoResource($this->whenLoaded('producto')),
            'cantidad' => $this->cantidad,
            'precio_lista' => $this->precio_lista,
            'descuento' => $this->descuento,
            'subtotal' => round($this->cantidad * $this->precio_lista * (1 - $this->descuento / 100), 2),
            'deleted_at' => $this->deleted_at,
        ];
    }
}
