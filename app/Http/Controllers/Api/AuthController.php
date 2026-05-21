<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    // -------------------------------------------------------
    // POST /api/auth/register
    // -------------------------------------------------------
    public function register(Request $request): JsonResponse
    {
        $data = $request->validate([
            'nombre'    => 'required|string|max:100',
            'apellido'  => 'required|string|max:100',
            'email'     => 'required|email|unique:usuarios,email',
            'password'  => 'required|string|min:8|confirmed',
            'telefono'  => 'nullable|string|max:20',
            'calle'     => 'nullable|string|max:200',
            'ciudad'    => 'nullable|string|max:100',
            'estado_dir'=> 'nullable|string|max:100',
            'codigo_postal' => 'nullable|string|max:20',
        ]);

        $usuario = Usuario::create([
            ...$data,
            'password' => Hash::make($data['password']),
            'rol'      => 1, // cliente por defecto
            'estado'   => 1, // activo por defecto
        ]);

        $token = $usuario->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Usuario registrado exitosamente.',
            'token'   => $token,
            'usuario' => [
                'id'             => $usuario->id,
                'nombre_completo'=> $usuario->nombre_completo,
                'email'          => $usuario->email,
                'rol'            => $usuario->rol,
            ],
        ], 201);
    }

    // -------------------------------------------------------
    // POST /api/auth/login
    // -------------------------------------------------------
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email'    => 'required|email',
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
                'message' => 'Tu cuenta está desactivada. Contacta al administrador.',
            ], 403);
        }

        // Revocar tokens anteriores y crear uno nuevo
        $usuario->tokens()->delete();
        $token = $usuario->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Inicio de sesión exitoso.',
            'token'   => $token,
            'usuario' => [
                'id'             => $usuario->id,
                'nombre_completo'=> $usuario->nombre_completo,
                'email'          => $usuario->email,
                'rol'            => $usuario->rol,
            ],
        ]);
    }

    // -------------------------------------------------------
    // POST /api/auth/logout
    // -------------------------------------------------------
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Sesión cerrada exitosamente.',
        ]);
    }

    // -------------------------------------------------------
    // GET /api/auth/me
    // -------------------------------------------------------
    public function me(Request $request): JsonResponse
    {
        $usuario = $request->user();

        return response()->json([
            'id'                 => $usuario->id,
            'nombre_completo'    => $usuario->nombre_completo,
            'email'              => $usuario->email,
            'telefono'           => $usuario->telefono,
            'direccion'          => $usuario->direccion_formateada,
            'rol'                => $usuario->rol,
            'estado'             => $usuario->estado,
        ]);
    }
}