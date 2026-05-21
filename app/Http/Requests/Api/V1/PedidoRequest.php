<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PedidoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $required = $this->isMethod('post') ? 'required' : 'sometimes';

        return [
            'usuario_id' => [$required, Rule::exists('usuarios', 'id')],
            'estado_pedido' => ['sometimes', 'integer', Rule::in([1, 2, 3, 4])],
            'fecha_pedido' => ['nullable', 'date'],
            'fecha_requerida' => ['nullable', 'date', 'after_or_equal:fecha_pedido'],
            'fecha_envio' => ['nullable', 'date', 'after_or_equal:fecha_pedido'],
            'tienda_id' => ['nullable', 'integer', 'min:1'],
            'personal_id' => ['nullable', 'integer', 'min:1'],
            'items' => ['sometimes', 'array'],
            'items.*.producto_id' => ['required_with:items', Rule::exists('productos', 'id')],
            'items.*.cantidad' => ['required_with:items', 'integer', 'min:1'],
            'items.*.precio_lista' => ['nullable', 'numeric', 'min:0'],
            'items.*.descuento' => ['nullable', 'numeric', 'between:0,100'],
        ];
    }
}
