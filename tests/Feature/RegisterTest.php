<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    /** @test */
    public function shoudCreateAnEmployeeUser()
    {
        $userAdmin = factory(\App\User::class)->create(['role' => 'admin']);
        $token = resolve(JsonWebToken::class)->generateToken($userAdmin->toArray());
        $authorizationHeader = ['Authorization' => "Bearer $token"];

        $userEmployee = factory(App\User::class)->make([
            'role' => 'employee'
        ]);

        $response = $this->withHeaders($authorizationHeader)
            ->postJson('/api/register', $userEmployee);

        $response->assertStatus(201);
    }

        /** @test */
    public function shoudCreateAnInternUser()
    {
        $userAdmin = factory(\App\User::class)->create(['role' => 'admin']);
        $token = resolve(JsonWebToken::class)->generateToken($userAdmin->toArray());
        $authorizationHeader = ['Authorization' => "Bearer $token"];

        $userIntern = factory(App\User::class)->make([
            'role' => 'intern'
        ]);

        $response = $this->withHeaders($authorizationHeader)
            ->postJson('/api/register', $userIntern);

        $response->assertStatus(201);
    }
}
