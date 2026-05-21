<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\V1\Concerns\RespondsWithJson;
use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    use RespondsWithJson;

    public function register(Request $request): JsonResponse
    {
        $data = $request->validate([
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'email' => 'required|email|unique:usuarios,email',
            'password' => 'required|string|min:8|confirmed',
            'telefono' => 'nullable|string|max:20',
            'calle' => 'nullable|string|max:200',
            'ciudad' => 'nullable|string|max:100',
            'estado_dir' => 'nullable|string|max:100',
            'codigo_postal' => 'nullable|string|max:20',
        ]);

        $usuario = Usuario::create([
            ...$data,
            'password' => Hash::make($data['password']),
            'rol' => 1,
            'estado' => 1,
        ]);

        $token = $usuario->createToken('auth_token')->plainTextToken;

        return $this->success([
            'token' => $token,
            'usuario' => [
                'id' => $usuario->id,
                'nombre_completo' => $usuario->nombre_completo,
                'email' => $usuario->email,
                'rol' => $usuario->rol,
            ],
        ], 'Usuario registrado exitosamente.', 201);
    }

    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $usuario = Usuario::where('email', $request->email)->first();

        if (!$usuario || !Hash::check($request->password, $usuario->password)) {
            throw ValidationException::withMessages([
                'email' => ['Las credenciales proporcionadas son incorrectas.'],
            ]);
        }

        if (!$usuario->isEnabled()) {
            return response()->json([
                'success' => false,
                'message' => 'Tu cuenta esta desactivada. Contacta al administrador.',
            ], 403);
        }

        $usuario->tokens()->delete();
        $token = $usuario->createToken('auth_token')->plainTextToken;

        return $this->success([
            'token' => $token,
            'usuario' => [
                'id' => $usuario->id,
                'nombre_completo' => $usuario->nombre_completo,
                'email' => $usuario->email,
                'rol' => $usuario->rol,
            ],
        ], 'Inicio de sesion exitoso.');
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return $this->success(null, 'Sesion cerrada exitosamente.');
    }

    public function me(Request $request): JsonResponse
    {
        $usuario = $request->user();

        return $this->success([
            'id' => $usuario->id,
            'nombre_completo' => $usuario->nombre_completo,
            'email' => $usuario->email,
            'telefono' => $usuario->telefono,
            'direccion' => $usuario->direccion_formateada,
            'rol' => $usuario->rol,
            'estado' => $usuario->estado,
        ], 'Usuario autenticado obtenido correctamente.');
    }
}
