<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * register user feature
     * 
     * @return void
     */
    public function testRegister()
    {
        $attributes = [
            'nome' => 'Frank Castle',
            'email' => 'thepunisher@gmail.com',
            'senha' => '1234567',
            'role' => 2,
        ];

        $response = $this->postJson('/api/register', $attributes);

        $response->assertStatus(201);

        $this->assertDatabaseHas('usuarios', [
            'email' => $attributes['email'],
        ]);

        array_splice($attributes, 2, 1);

        $response->assertJsonFragment([
            'success' => true,
            'user_data' => $attributes
        ]);
    }
}
