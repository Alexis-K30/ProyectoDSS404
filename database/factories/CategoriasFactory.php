<?php

namespace Database\Factories;

use App\Models\Categorias;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Categorias>
 */
class CategoriasFactory extends Factory
{
    protected $model = Categorias::class;

    public function definition(): array
    {
        return [
            'nombre_categoria' => fake('es_ES')->unique()->randomElement([
                'Bicicletas urbanas',
                'Cascos certificados',
                'Luces y seguridad',
                'Herramientas de taller',
                'Llantas y neumaticos',
                'Transmision',
                'Frenos hidraulicos',
                'Ropa impermeable',
            ]),
        ];
    }
}
