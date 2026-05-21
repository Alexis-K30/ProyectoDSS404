<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Gate;
use App\Models\Usuario;
use App\Models\Pedido;
use App\Policies\PedidoPolicy;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\Request;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
{
    Schema::defaultStringLength(191);

    Gate::policy(Pedido::class, PedidoPolicy::class);

    // Gates
    Gate::before(function (Usuario $usuario, string $ability) {
        if ($usuario->esAdmin) {
            return true;
        }
    });

    Gate::define('gestionar-productos',  fn(Usuario $u) => $u->rol === 3);
    Gate::define('gestionar-categorias', fn(Usuario $u) => $u->rol === 3);
    Gate::define('gestionar-usuarios',   fn(Usuario $u) => $u->rol === 3);
    Gate::define('ver-todos-pedidos',    fn(Usuario $u) => $u->rol === 3);

    Gate::define('gestionar-pedido', function (Usuario $u, Pedido $pedido) {
        return $u->id === $pedido->usuario_id || $u->rol === 3;
    });

    // Rate limiting
    RateLimiter::for('api', function (Request $request) {
        return Limit::perMinute(60)->by(
            $request->user()?->id ?: $request->ip()
        );
    });

    RateLimiter::for('auth', function (Request $request) {
        return Limit::perMinute(10)->by($request->ip());
    });
}
}
