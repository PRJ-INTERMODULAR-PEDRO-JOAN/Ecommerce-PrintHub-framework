<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // 1. IMPRESORAS (Categoría 'Impresoras')
        Product::create([
            'sku' => 'IMP-ENDER3',
            'name' => 'Impresora Ender 3',
            'description' => 'La impresora 3D más confiable para principiantes. Alta precisión y fácil montaje.',
            'price' => 179.99,
            'stock' => 10,
            'category' => 'Impresoras',
            'image' => 'ender.jpg'
        ]);

        Product::create([
            'sku' => 'FIL-BAMBU',
            'name' => 'Filamento Bambu Lab',
            'description' => 'Bobina de PLA original.',
            'price' => 24.99,
            'stock' => 50,
            'category' => 'Impresoras',
            'image' => 'bambu.jpg'
        ]);

        Product::create([
            'sku' => 'IMP-PRUSA',
            'name' => 'Prusa MK4',
            'description' => 'Original Prusa MK4. La referencia en impresión 3D profesional de escritorio.',
            'price' => 899.00,
            'stock' => 3,
            'category' => 'Impresoras',
            'image' => 'prusa.jpg'
        ]);

        // 3. DESTACADOS (Figuras, Maquetas, Coches...)
        Product::create([
            'sku' => 'FIG-LAMBO',
            'name' => 'Lamborghini Aventador',
            'description' => 'Maqueta detallada a escala 1:18 impresa en resina de alta calidad.',
            'price' => 45.00,
            'stock' => 2,
            'category' => 'Coches',
            'image' => 'lambo_2.jpg' // Tu imagen del Lambo
        ]);

        Product::create([
            'sku' => 'FIG-LINK',
            'name' => 'Figura Link (Zelda)',
            'description' => 'Figura pintada a mano de Link con la Espada Maestra.',
            'price' => 35.00,
            'stock' => 5,
            'category' => 'Figuras',
            'image' => 'FiguraLink.jpg'
        ]);

        Product::create([
            'sku' => 'FIG-AATROX',
            'name' => 'Figura Aatrox',
            'description' => 'Figura detallada de Aatrox, La Espada de los Oscuros.',
            'price' => 45.00,
            'stock' => 2,
            'category' => 'Figuras',
            'image' => 'aatrox.jpg'
        ]);

        Product::create([
            'sku' => 'MAQ-SAGRADA',
            'name' => 'Maqueta Sagrada Familia',
            'description' => 'Réplica arquitectónica impresa en resina de alta definición.',
            'price' => 120.00,
            'stock' => 1,
            'category' => 'Maquetas',
            'image' => 'Maqueta_Sagrada_Familia.jpg'
        ]);
        
        
    }
}