<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Usuario Administrador (usando tus campos)
        User::factory()->create([
            'name' => 'Admin',
            'surname' => 'PrintHub',
            'email' => 'admin@printhub.com',
            'phone' => '600123456',
            'role' => 'admin',
            'password' => bcrypt('password'),
        ]);

        // Usuario de prueba (ejemplo del json 'Mec')
        User::factory()->create([
            'name' => 'Mec',
            'surname' => 'User',
            'email' => 'mec@example.com',
            'role' => 'user',
            'password' => bcrypt('password'),
        ]);

        // Cargar productos
        $this->call(ProductSeeder::class);
    }
}