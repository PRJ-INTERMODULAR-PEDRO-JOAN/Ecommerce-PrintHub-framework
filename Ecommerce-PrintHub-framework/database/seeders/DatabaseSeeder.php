<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Usuario ADMIN (El que tenías en users.json)
        User::create([
            'name' => 'Admin',
            'surname' => 'PrintHub',
            'email' => 'admin@printhub.com',
            'phone' => '666777888',
            'role' => 'admin',
            'password' => Hash::make('password'), // Contraseña segura
        ]);

        // 2. Usuario CLIENTE (Ejemplo 'Mec' del json antiguo)
        User::create([
            'name' => 'Mec',
            'surname' => 'Cliente',
            'email' => 'mec@printhub.com',
            'phone' => '600123456',
            'role' => 'user',
            'password' => Hash::make('password'), // Contraseña segura
        ]);

        // 3. Cargamos los productos (Lambo, impresoras, etc.)
        $this->call(ProductSeeder::class);
    }
}