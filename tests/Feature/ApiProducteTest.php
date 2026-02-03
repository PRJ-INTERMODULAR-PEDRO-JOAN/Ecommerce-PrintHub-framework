<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Product;

class ApiProductTest extends TestCase
{
    use RefreshDatabase; // Borra la BD después de cada test

    /** @test */
    public function la_api_devuelve_lista_de_productos()
    {
        // 1. Crear datos de prueba
        Product::create([
            'sku' => 'TEST-001',
            'name' => 'Impresora Test',
            'description' => 'Desc',
            'price' => 200.50,
            'stock' => 10,
            'category' => '3D',
            'image' => 'img.jpg'
        ]);

        // 2. Llamar a la API
        $response = $this->getJson('/api/products');

        // 3. Verificar que da OK (200) y contiene el producto
        $response->assertStatus(200)
                 ->assertJsonFragment(['name' => 'Impresora Test']);
    }

    /** @test */
    public function la_api_devuelve_un_producto_individual()
    {
        $product = Product::create([
            'sku' => 'TEST-002', 'name' => 'Producto Unico', 'price' => 50, 'stock' => 5, 'category' => 'A', 'image' => 'a.jpg'
        ]);

        $response = $this->getJson('/api/products/' . $product->id);

        $response->assertStatus(200)
                 ->assertJson(['name' => 'Producto Unico']);
    }

    /** @test */
    public function devuelve_404_si_producto_no_existe()
    {
        $response = $this->getJson('/api/products/99999');
        $response->assertStatus(404);
    }
}