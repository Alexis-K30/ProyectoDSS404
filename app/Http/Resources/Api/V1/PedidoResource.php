<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PedidoResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'usuario_id' => $this->usuario_id,
            'usuario' => new UsuarioResource($this->whenLoaded('usuario')),
            'estado_pedido' => $this->estado_pedido,
            'etiqueta_estado' => $this->etiqueta_estado,
            'fecha_pedido' => $this->fecha_pedido,
            'fecha_requerida' => $this->fecha_requerida,
            'fecha_envio' => $this->fecha_envio,
            'tienda_id' => $this->tienda_id,
            'personal_id' => $this->personal_id,
            'items' => ItemPedidoResource::collection($this->whenLoaded('items')),
            'total' => $this->whenLoaded('items', fn () => round($this->total, 2)),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
        ];
    }
}
