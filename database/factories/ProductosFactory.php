<?php

namespace Database\Factories;

use App\Models\Productos;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Productos>
 */
class ProductosFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\Illuminate\Database\Eloquent\Model>
     */
    protected $model = Productos::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nombre_producto' => fake('es_ES')->words(3, true),
            'marca_id'        => fake()->numberBetween(1, 10),
            'categoria_id'    => fake()->numberBetween(1, 5), // Corresponde a las 5 categorías del seeder
            'modelo_anio'     => fake()->numberBetween(2018, 2024),
            'precio_lista'    => fake()->randomFloat(2, 50, 3000), // Precio entre 50.00 y 3000.00
        ];
    }
}
