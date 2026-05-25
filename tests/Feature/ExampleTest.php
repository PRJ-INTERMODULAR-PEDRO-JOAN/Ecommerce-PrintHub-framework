<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        // Tu aplicación protege la ruta raíz y redirige al login, por lo que devuelve 302 en vez de 200.
        $response->assertStatus(302);
    }
}