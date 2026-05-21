<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ItemPedidoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $required = $this->isMethod('post') ? 'required' : 'sometimes';

        return [
            'pedido_id' => [$required, Rule::exists('pedidos', 'id')],
            'item_id' => ['sometimes', 'integer', 'min:1'],
            'producto_id' => [$required, Rule::exists('productos', 'id')],
            'cantidad' => [$required, 'integer', 'min:1'],
            'precio_lista' => ['nullable', 'numeric', 'min:0'],
            'descuento' => ['nullable', 'numeric', 'between:0,100'],
        ];
    }
}
