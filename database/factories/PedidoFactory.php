<?php

namespace Database\Factories;

use App\Models\Pedido;
use App\Models\Usuario;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pedido>
 */
class PedidoFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\Illuminate\Database\Eloquent\Model>
     */
    protected $model = Pedido::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $fechaPedido = fake()->dateTimeBetween('-1 year', 'now');
        $fechaRequerida = (clone $fechaPedido)->modify('+' . rand(3, 10) . ' days');
        
        $estado = fake()->randomElement([1, 2, 3, 4]);
        
        // Si el estado es 4 (Completado), generamos una fecha de envío. Si no, es null.
        $fechaEnvio = ($estado === 4) 
            ? (clone $fechaPedido)->modify('+' . rand(1, 5) . ' days') 
            : null;

        return [
            'usuario_id'      => Usuario::factory(),
            'estado_pedido'   => $estado,
            'fecha_pedido'    => $fechaPedido,
            'fecha_requerida' => $fechaRequerida,
            'fecha_envio'     => $fechaEnvio,
            'tienda_id'       => fake()->numberBetween(1, 3),
            'personal_id'     => fake()->numberBetween(1, 10),
        ];
    }
}
