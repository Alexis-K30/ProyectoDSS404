<?php

namespace Database\Seeders;

use App\Models\Usuario;
use App\Models\Categorias as Categoria;
use App\Models\Productos as Producto;
use App\Models\Pedido;
use App\Models\Items_Pedidos as ItemPedido;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Usuario::firstOrCreate(
            ['email' => 'admin@tienda.com'],
            [
                'nombre'   => 'Administrador',
                'apellido' => 'Principal',
                'password' => \Illuminate\Support\Facades\Hash::make('admin1234'),
                'rol'      => 3,
                'estado'   => 1,
            ]
        );

        // Categorias
        Categoria::insert([
            ['nombre_categoria' => 'Bicicletas de Montana', 'created_at' => now(), 'updated_at' => now()],
            ['nombre_categoria' => 'Bicicletas de Ruta',    'created_at' => now(), 'updated_at' => now()],
            ['nombre_categoria' => 'Accesorios',            'created_at' => now(), 'updated_at' => now()],
            ['nombre_categoria' => 'Componentes',           'created_at' => now(), 'updated_at' => now()],
            ['nombre_categoria' => 'Ropa Deportiva',        'created_at' => now(), 'updated_at' => now()],
        ]);

        // Productos
        Producto::factory(50)->create();

        // Usuarios clientes con pedidos
        Usuario::factory(40)->create(['rol' => 1])->each(function (Usuario $usuario) {
            Pedido::factory(rand(1, 5))
                ->for($usuario)
                ->create()
                ->each(function (Pedido $pedido) {
                    $productos = Producto::inRandomOrder()->limit(rand(1, 4))->get();
                    $productos->each(function (Producto $producto, int $idx) use ($pedido) {
                        ItemPedido::create([
                            'pedido_id'    => $pedido->id,
                            'item_id'      => $idx + 1,
                            'producto_id'  => $producto->id,
                            'cantidad'     => rand(1, 5),
                            'precio_lista' => $producto->precio_lista,
                            'descuento'    => fake()->randomElement([0, 5, 10, 15, 20]),
                        ]);
                    });
                });
        });
    }
}
