<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $required = $this->isMethod('post') ? 'required' : 'sometimes';

        return [
            'nombre_producto' => [$required, 'string', 'max:255'],
            'marca_id' => ['nullable', 'integer', 'min:1'],
            'categoria_id' => ['nullable', Rule::exists('categorias', 'id')],
            'modelo_anio' => ['nullable', 'integer', 'between:1900,' . (now()->year + 1)],
            'precio_lista' => [$required, 'numeric', 'min:0', 'max:99999999.99'],
        ];
    }
}
