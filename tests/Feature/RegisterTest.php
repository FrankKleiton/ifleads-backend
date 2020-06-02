<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;
use App\Services\Auth\JsonWebToken;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function shoudCreateAnEmployeeUser()
    {
        $userAdmin = factory(User::class)->create(['role' => 'admin']);
        $token = resolve(JsonWebToken::class)->generateToken($userAdmin->toArray());
        $authorizationHeader = ['Authorization' => "Bearer $token"];

        $body = [
            'name' => 'Employee',
            'email' => 'employee@email.com',
            'password' => 'employee123',
            'role' => 'employee'
        ];

        $response = $this->withHeaders($authorizationHeader)
            ->postJson('/api/admin/register', $body);

        $response->assertStatus(201)
            ->assertJson([
                'name' => $body['name'],
                'email' => $body['email'],
                'role' => $body['role'],
            ]);

    }

        /** @test */
    public function shoudCreateAnInternUser()
    {
        $userAdmin = factory(User::class)->create(['role' => 'admin']);
        $token = resolve(JsonWebToken::class)->generateToken($userAdmin->toArray());
        $authorizationHeader = ['Authorization' => "Bearer $token"];

        $body = [
            'name' => 'Employee',
            'email' => 'employee@email.com',
            'password' => 'employee123',
            'role' => 'intern'
        ];

        $response = $this->withHeaders($authorizationHeader)
            ->postJson('/api/admin/register', $body);

        $response->assertStatus(201)
            ->assertJson([
                'name' => $body['name'],
                'email' => $body['email'],
                'role' => $body['role'],
            ]);
    }
}
