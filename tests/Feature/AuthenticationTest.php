<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * @author franklynkleiton
 */
class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function registerRequestCreateUser()
    {
        $attributes = [
            'nome' => 'Frank Castle',
            'email' => 'thepunisher@gmail.com',
            'password' => '1234567',
            'role' => 2,
        ];

        $response = $this->postJson('/api/register', $attributes);

        $response->assertStatus(201);

        array_splice($attributes, 2, 1);

        $response->assertJsonFragment([
            'success' => true,
            'user_data' => $attributes
        ]);

        $this->assertDatabaseHas('usuarios', [
            'email' => $attributes['email'],
        ]);
    }

    /** @test */
    public function registeredUserCanLoggin()
    {
        $usuario = factory(\App\Usuario::class)->create();

        $response = $this->postJson('/api/login', [
            'email' => $usuario->email, 'password' => '123456'
        ]);

        $this->assertTrue($response['success']);
        $this->assertNotEmpty($response['token']);
    }
}
