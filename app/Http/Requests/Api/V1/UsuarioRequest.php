<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UsuarioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $usuarioId = $this->route('usuario')?->id;
        $required = $this->isMethod('post') ? 'required' : 'sometimes';

        return [
            'nombre' => [$required, 'string', 'max:100'],
            'apellido' => [$required, 'string', 'max:100'],
            'email' => [$required, 'email', Rule::unique('usuarios', 'email')->ignore($usuarioId)],
            'password' => [$this->isMethod('post') ? 'required' : 'nullable', 'string', 'min:8', 'confirmed'],
            'telefono' => ['nullable', 'string', 'max:25'],
            'calle' => ['nullable', 'string', 'max:191'],
            'ciudad' => ['nullable', 'string', 'max:50'],
            'estado_dir' => ['nullable', 'string', 'max:25'],
            'codigo_postal' => ['nullable', 'string', 'max:5'],
            'rol' => ['sometimes', 'integer', Rule::in([1, 2, 3])],
            'estado' => ['sometimes', 'integer', Rule::in([0, 1, 2])],
        ];
    }
}
