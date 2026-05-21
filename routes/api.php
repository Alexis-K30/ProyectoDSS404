<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\V1\CategoriaController;
use App\Http\Controllers\Api\V1\ItemPedidoController;
use App\Http\Controllers\Api\V1\PedidoController;
use App\Http\Controllers\Api\V1\ProductoController;
use App\Http\Controllers\Api\V1\UsuarioController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function (): void {
    Route::prefix('auth')->middleware('throttle:auth')->group(function (): void {
        Route::post('register', [AuthController::class, 'register']);
        Route::post('login', [AuthController::class, 'login']);
    });

    Route::middleware(['auth:sanctum', 'throttle:api'])->group(function (): void {
        Route::prefix('auth')->group(function (): void {
            Route::post('logout', [AuthController::class, 'logout']);
            Route::get('me', [AuthController::class, 'me']);
        });

        Route::apiResource('usuarios', UsuarioController::class);
        Route::patch('usuarios/{usuario}/restore', [UsuarioController::class, 'restore']);

        Route::apiResource('categorias', CategoriaController::class);
        Route::patch('categorias/{categoria}/restore', [CategoriaController::class, 'restore']);

        Route::apiResource('productos', ProductoController::class);
        Route::patch('productos/{producto}/restore', [ProductoController::class, 'restore']);

        Route::apiResource('pedidos', PedidoController::class);
        Route::patch('pedidos/{pedido}/restore', [PedidoController::class, 'restore']);

        Route::apiResource('items-pedido', ItemPedidoController::class)
            ->parameters(['items-pedido' => 'itemPedido']);
        Route::patch('items-pedido/{itemPedido}/restore', [ItemPedidoController::class, 'restore']);
    });
});
