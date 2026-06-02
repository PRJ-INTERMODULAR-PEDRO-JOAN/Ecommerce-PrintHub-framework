<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class SecondHandProductSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Borramos los productos de segunda mano anteriores para evitar duplicados
        Product::where('is_second_hand', true)->delete();

        // 2. Creamos los productos con el nuevo proveedor de imágenes (placehold.co)
        Product::create([
            'name' => 'Impresora 3D Creality Ender 3 (Reacondicionada)',
            'description' => 'Impresora 3D de segunda mano en perfecto estado. Incluye cama de cristal mejorada y boquilla nueva. Revisada por nuestros técnicos.',
            'price' => 120.50,
            'stock' => 2,
            'image' => 'https://placehold.co/300x200/FFF3CD/000000?text=Ender+3+Usada',
            'category' => 'Impresoras 3D',
            'is_second_hand' => true,
            'sku' => 'SH-END3-001',
        ]);

        Product::create([
            'name' => 'Lote de Filamento PLA Colores (Abierto)',
            'description' => 'Varios rollos de filamento PLA de diferentes colores. Fueron usados para pruebas de impresión, queda aproximadamente un 70% de material en cada rollo.',
            'price' => 15.00,
            'stock' => 5,
            'image' => 'https://placehold.co/300x200/FFF3CD/000000?text=PLA+Abierto',
            'category' => 'Filamentos',
            'is_second_hand' => true,
            'sku' => 'SH-PLA-002',
        ]);

        Product::create([
            'name' => 'Raspberry Pi 4 Modelo B 4GB (Usada)',
            'description' => 'Placa base perfecta para usar con OctoPrint y controlar tu impresora a distancia. Incluye carcasa con ventilador y fuente de alimentación original.',
            'price' => 45.00,
            'stock' => 1,
            'image' => 'https://placehold.co/300x200/FFF3CD/000000?text=Raspberry+Pi+4',
            'category' => 'Componentes',
            'is_second_hand' => true,
            'sku' => 'SH-RPI4-003',
        ]);
        
        Product::create([
            'name' => 'Extrusor Directo BMG (Pieza de despiece)',
            'description' => 'Extrusor directo retirado de una máquina en funcionamiento. Los engranajes están limpios y el motor paso a paso funciona sin tirones.',
            'price' => 18.90,
            'stock' => 3,
            'image' => 'https://placehold.co/300x200/FFF3CD/000000?text=Extrusor+Usado',
            'category' => 'Repuestos',
            'is_second_hand' => true,
            'sku' => 'SH-EXT-004',
        ]);
    }
}