<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    /**
     * register user feature
     * 
     * @return void
     */
    public function testRegister()
    {
        $response = $this->postJson('/api/register', [
            'nome' => 'Frank Castle',
            'email' => 'thepunisher@gmail.com',
            'senha' => bcrypt('1234567'),
            'role' => 2,
        ]);
        
        $response->dump();
        $response->assertStatus(201);
        $response->assertJsonFragment([
            'nome' => 'Frank Castle',
            'email' => 'thepunisher@gmail.com',
            'token' => ''
        ]);
    }
}
