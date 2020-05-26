<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginTest extends TestCase
{

    use RefreshDatabase;

    /** @test */
    public function shouldAuthenticateAnUser()
    {
        $user = factory(\App\User::class)->create([
            'password' => 'testpassword'
        ]);
        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'testpassword'
        ]);

        $response->dump();
        $response->assertStatus(200);
    }
}
