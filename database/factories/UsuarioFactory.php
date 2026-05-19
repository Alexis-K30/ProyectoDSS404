<?php

namespace Database\Factories;

use App\Models\Usuario;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<Usuario>
 */
class UsuarioFactory extends Factory
{
    protected $model = Usuario::class;

    public function definition(): array
    {
        return [
            'nombre'        => fake('es_ES')->firstName(),
            'apellido'      => fake('es_ES')->lastName(),
            'email'         => fake()->unique()->safeEmail(),
            'password'      => bcrypt('password'),
            'telefono'      => fake()->numerify('7###-####'),
            'calle'         => fake('es_ES')->streetAddress(),
            'ciudad'        => fake('es_ES')->city(),
            'estado_dir'    => fake('es_ES')->state(),
            'codigo_postal' => fake()->numerify('#####'),
            'rol'           => fake()->randomElement([1, 1, 1, 2, 3]),
            'estado'        => fake()->randomElement([1, 1, 1, 0]),
            'email_verificado_en' => fake()->optional(0.85)->dateTime(),
        ];
    }

    public function admin(): static
    {
        return $this->state(['rol' => 3, 'estado' => 1]);
    }

    public function bloqueado(): static
    {
        return $this->state(['estado' => 0]);
    }
}

