<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Product;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

class ApiCommentTest extends TestCase
{
    use RefreshDatabase;

    public function test_se_pueden_ver_comentarios_de_un_producto()
    {
        $product = Product::create(['sku'=>'P1','name'=>'P','price'=>10,'stock'=>1,'category'=>'C','image'=>'i.jpg']);
        
        // Crear usuario y comentario asociado
        $user = User::factory()->create();
        $product->comments()->create([
            'user_id' => $user->id,
            'text' => 'Comentario de prueba',
            'rating' => 5
        ]);

        $response = $this->getJson("/api/products/{$product->id}/comments");

        $response->assertStatus(200)
                 ->assertJsonFragment(['text' => 'Comentario de prueba']);
    }

    public function test_usuario_autenticado_puede_comentar()
    {
        $product = Product::create(['sku'=>'P2','name'=>'P2','price'=>10,'stock'=>1,'category'=>'C','image'=>'i.jpg']);
        $user = User::factory()->create();

        // Autenticamos como este usuario
        Sanctum::actingAs($user);

        $response = $this->postJson("/api/products/{$product->id}/comments", [
            'text' => 'Me encanta',
            'rating' => 5
        ]);

        $response->assertStatus(201); // Created
        $this->assertDatabaseHas('comments', ['text' => 'Me encanta']);
    }

    public function test_usuario_anonimo_no_puede_comentar()
    {
        $product = Product::create(['sku'=>'P3','name'=>'P3','price'=>10,'stock'=>1,'category'=>'C','image'=>'i.jpg']);

        // Sin autenticar
        $response = $this->postJson("/api/products/{$product->id}/comments", [
            'text' => 'Intento hack',
            'rating' => 1
        ]);

        $response->assertStatus(401); // Unauthorized
    }

    public function test_validacion_falla_si_faltan_datos()
    {
        $product = Product::create(['sku'=>'P4','name'=>'P4','price'=>10,'stock'=>1,'category'=>'C','image'=>'i.jpg']);
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        // Enviamos vacío
        $response = $this->postJson("/api/products/{$product->id}/comments", []);

        $response->assertStatus(422) // Error de validación
                 ->assertJsonValidationErrors(['text', 'rating']);
    }
}