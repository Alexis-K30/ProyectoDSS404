<?php

namespace App\Policies;

use App\Models\Pedido;
use App\Models\Usuario;

class PedidoPolicy
{
    public function viewAny(Usuario $usuario): bool
    {
        return $usuario->rol === 3;
    }

    public function view(Usuario $usuario, Pedido $pedido): bool
    {
        return $this->manage($usuario, $pedido);
    }

    public function update(Usuario $usuario, Pedido $pedido): bool
    {
        return $this->manage($usuario, $pedido);
    }

    public function delete(Usuario $usuario, Pedido $pedido): bool
    {
        return $this->manage($usuario, $pedido);
    }

    public function restore(Usuario $usuario, Pedido $pedido): bool
    {
        return $this->manage($usuario, $pedido);
    }

    private function manage(Usuario $usuario, Pedido $pedido): bool
    {
        return $usuario->rol === 3 || $usuario->id === $pedido->usuario_id;
    }
}
