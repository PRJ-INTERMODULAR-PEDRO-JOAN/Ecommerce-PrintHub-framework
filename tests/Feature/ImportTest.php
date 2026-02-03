<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class ImportTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admin_puede_ver_pagina_importar()
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'email' => 'admin@test.com'
        ]);

        $response = $this->actingAs($admin)->get('/admin/import');

        $response->assertStatus(200);
    }

    /** @test */
    public function usuario_normal_no_puede_ver_pagina_importar()
    {
        $user = User::factory()->create([
            'role' => 'user',
            'email' => 'user@test.com'
        ]);

        $response = $this->actingAs($user)->get('/admin/import');

        $response->assertStatus(403); // Forbidden
    }

    /** @test */
    public function invitado_es_redirigido_al_login()
    {
        $response = $this->get('/admin/import');

        $response->assertStatus(302); // Redirección
        $response->assertRedirect('/login');
    }
}