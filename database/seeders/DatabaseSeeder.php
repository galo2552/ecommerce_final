<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Crear Usuarios de prueba (Admin y Cliente)
        $admin = \App\Models\User::create([
            'name' => 'Administrador',
            'email' => 'admin@admin.com',
            'password' => bcrypt('admin123'),
            'role' => 'admin',
        ]);

        $cliente = \App\Models\User::create([
            'name' => 'Cliente Prueba',
            'email' => 'cliente@cliente.com',
            'password' => bcrypt('cliente123'),
            'role' => 'cliente',
        ]);

        // 2. Crear Categorías
        $catRunning = \App\Models\Category::create(['name' => 'Running', 'description' => 'Zapatillas para correr']);
        $catUrbana = \App\Models\Category::create(['name' => 'Urbana', 'description' => 'Zapatillas de uso diario']);

        // 3. Crear Productos (Zapatillas) y asignarles categorías
        $prod1 = \App\Models\Product::create([
            'name' => 'Nike Air Zoom',
            'brand' => 'Nike',
            'description' => 'Alta velocidad y confort.',
            'price' => 120000,
        ]);
        $prod1->categories()->attach($catRunning->id);

        $prod2 = \App\Models\Product::create([
            'name' => 'Vans Old Skool',
            'brand' => 'Vans',
            'description' => 'Clásicas de skate.',
            'price' => 85000,
        ]);
        $prod2->categories()->attach($catUrbana->id);

        // 4. Crear Dirección para el cliente
        $direccion = \App\Models\Address::create([
            'user_id' => $cliente->id,
            'street' => 'Av. Rivadavia 1234',
            'city' => 'General Rodriguez',
            'province' => 'Buenos Aires',
            'zip_code' => '1940'
        ]);

        // 5. Crear Pedidos con distintos estados
        $pedido1 = \App\Models\Order::create([
            'user_id' => $cliente->id,
            'address_id' => $direccion->id,
            'total' => 120000,
            'status' => 'entregado',
        ]);
        $pedido1->items()->attach($prod1->id, ['quantity' => 1, 'unit_price' => 120000, 'size' => '42']);

        $pedido2 = \App\Models\Order::create([
            'user_id' => $cliente->id,
            'address_id' => $direccion->id,
            'total' => 85000,
            'status' => 'pendiente',
        ]);
        $pedido2->items()->attach($prod2->id, ['quantity' => 1, 'unit_price' => 85000, 'size' => '39']);
    }
}
