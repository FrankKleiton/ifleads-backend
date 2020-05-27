<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Services\Auth\JsonWebToken;
use App\User;

class LostMaterialsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function shouldCreateANewLostMaterial()
    {
        $user = factory(User::class)->create();
        $token = resolve(JsonWebToken::class)->generateToken($user->toArray());
        $authorizationHeader = ['Authorization' => "Bearer $token"];

        $body = [
            'name' => 'Lost Material Test',
            'description' => 'Lost material only for test',
            'returner_registration_mark' => '20161038060041'
        ];

        $response = $this->withHeaders($authorizationHeader)
            ->postJson('/api/materials/losts', $body);

        $response->assertStatus(201)
            ->assertJson([
                'name' => $body['name'],
                'description' => $body['description'],
                'returner_registration_mark' => $body['returner_registration_mark']
            ]);
    }

    /** @test */
    public function shouldCheckTypesOfInputsWhenForCreateANewLostMaterial()
    {
        $user = factory(User::class)->create();
        $token = resolve(JsonWebToken::class)->generateToken($user->toArray());
        $authorizationHeader = ['Authorization' => "Bearer $token"];

        $bodyWithNameTypeIncorrect = [
            'name' => 123,
            'description' => 'Lost material only for test',
            'returner_registration_mark' => '20161038060041'
        ];
        $response = $this->withHeaders($authorizationHeader)
            ->postJson('/api/materials/losts', $bodyWithNameTypeIncorrect);
        $response->assertStatus(422);

        $bodyWithDescriptionTypeIncorrect = [
            'name' => 'Lost Material Test',
            'description' => 123,
            'returner_registration_mark' => '20161038060041'
        ];
        $response = $this->withHeaders($authorizationHeader)
            ->postJson('/api/materials/losts', $bodyWithDescriptionTypeIncorrect);
        $response->assertStatus(422);

        $bodyWithMatriculaTypeIncorrect = [
            'name' => 'Lost Material Test',
            'description' => 'Lost material only for test',
            'returner_registration_mark' => 20161038060041
        ];
        $response = $this->withHeaders($authorizationHeader)
            ->postJson('/api/materials/losts', $bodyWithMatriculaTypeIncorrect);
        $response->assertStatus(422);
    }
}
