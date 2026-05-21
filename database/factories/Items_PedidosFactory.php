<?php

namespace Database\Factories;

use App\Models\Items_Pedidos;
use App\Models\Pedido;
use App\Models\Productos;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Items_Pedidos>
 */
class Items_PedidosFactory extends Factory
{
    protected $model = Items_Pedidos::class;

    public function definition(): array
    {
        $producto = Productos::factory()->create();

        return [
            'pedido_id' => Pedido::factory(),
            'item_id' => 1,
            'producto_id' => $producto->id,
            'cantidad' => fake()->numberBetween(1, 5),
            'precio_lista' => $producto->precio_lista,
            'descuento' => fake()->randomElement([0, 5, 10, 15]),
        ];
    }
}
