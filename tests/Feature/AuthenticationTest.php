<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

/**
 * @author franklynkleiton
 */
class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function adminCanRegisterEmployee()
    {
        $this->markTestSkipped('Skipping test until find token header solution');
        $user = factory(\App\Usuario::class)->create(['role' => 1]);
        
        $attributes = [
            'nome' => 'Frank Castle',
            'email' => 'thepunisher@gmail.com',
            'password' => '1234567',
            'role' => 2,
        ];


         $response = $this->postJson('/api/register', $attributes, [
            'token' => auth()->login($user)
        ]);
        $response->dump();
        $response->assertCreated();
        
        array_splice($attributes, 2, 1);
        
        $response->assertJsonFragment([
            'success' => true,
            'user_data' => $attributes,
        ]);
    }

    /** @test */
    public function registeredUserCanLoggin()
    {
        $user = factory(\App\Usuario::class)->create();

        $response = $this->postJson('/api/login', [
            'email' => $user->email, 'password' => '123456',
        ]);
        
        $response->assertOk();

        $this->assertTrue($response['success']);
        $this->assertNotEmpty($response['token']);
        $this->assertAuthenticatedAs($user);
    }

    /**
     * @test
     * 
     * The call method was used to pass the user token
     * in url due to headers problems in jwt tests.
     */
    public function userCanLogout()
    {
        $user = factory(\App\Usuario::class)->create();
        $response = $this->call('GET', '/api/logout', [
            'token' => auth()->login($user),
        ]);
        $response->assertOk();
    }

    /** @test */
    public function unauthenticatedUserCantLogout()
    {
        $response = $this->getJson('/api/logout');
        $response->assertUnauthorized();
    }
}
