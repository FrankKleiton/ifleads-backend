<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Services\Auth\JsonWebToken;
use App\User;
use App\Material;

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
            ->postJson('/api/losts/materials', $body);

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
            ->postJson('/api/losts/materials', $bodyWithNameTypeIncorrect);
        $response->assertStatus(422);

        $bodyWithDescriptionTypeIncorrect = [
            'name' => 'Lost Material Test',
            'description' => 123,
            'returner_registration_mark' => '20161038060041'
        ];
        $response = $this->withHeaders($authorizationHeader)
            ->postJson('/api/losts/materials', $bodyWithDescriptionTypeIncorrect);
        $response->assertStatus(422);

        $bodyWithMatriculaTypeIncorrect = [
            'name' => 'Lost Material Test',
            'description' => 'Lost material only for test',
            'returner_registration_mark' => 20161038060041
        ];
        $response = $this->withHeaders($authorizationHeader)
            ->postJson('/api/losts/materials', $bodyWithMatriculaTypeIncorrect);

        $response->assertStatus(422);
    }

    /** @test */
    public function shouldRegistrateWhoTookTheLostMaterial()
    {
        $user = factory(User::class)->create();
        $token = resolve(JsonWebToken::class)->generateToken($user->toArray());
        $authorizationHeader = ['Authorization' => "Bearer $token"];

        $lostMaterial = factory(Material::class)->create([
            'tooker_registration_mark' => null
        ]);

        $body = [
            'tooker_registration_mark' => '20161038060041'
        ];

        $response = $this->withHeaders($authorizationHeader)
            ->patchJson("/api/losts/materials/$lostMaterial->id", $body);

        $response->assertStatus(200);

        $this->assertDatabaseHas('materials', [
            'tooker_registration_mark' => $body['tooker_registration_mark']
        ]);
    }

    /** @test */
    public function shouldThrowAnErrorIfLostMaterialNotHasBeenFound()
    {
        $user = factory(User::class)->create();
        $token = resolve(JsonWebToken::class)->generateToken($user->toArray());
        $authorizationHeader = ['Authorization' => "Bearer $token"];

        $body = [
            'tooker_registration_mark' => '20161038060041'
        ];

        $nonexistentId = 1;

        $response = $this->withHeaders($authorizationHeader)
            ->patchJson("/api/losts/materials/$nonexistentId", $body);

        $response->assertStatus(404)
            ->assertJson([
                'message' => 'No query results for model [App\Material] 1'
            ]);
    }

    /** test */
    public function shouldCheckTypeTookerRegistrationMarkInput()
    {
        $user = factory(User::class)->create();
        $token = resolve(JsonWebToken::class)->generateToken($user->toArray());
        $authorizationHeader = ['Authorization' => "Bearer $token"];

        $lostMaterial = factory(Material::class)->create([
            'tooker_registration_mark' => null
        ]);

        $incorrectBody = [
            'tooker_registration_mark' => 20161038060041
        ];

        $response = $this->withHeaders($authorizationHeader)
            ->patchJson("/api/losts/materials/$lostMaterial->id", $incorrectBody);

        $response->assertStatus(422);
    }

    /** @test **/
    public function shouldListAllLostMaterials()
    {
        $user = factory(User::class)->create();
        $token = resolve(JsonWebToken::class)->generateToken($user->toArray());
        $authorizationHeader = ['Authorization' => "Bearer $token"];

        factory(Material::class, 3)->create();
        factory(Material::class, 2)->create([
            'tooker_registration_mark' => null
        ]);
        
        factory(Material::class, 2)->create([
            'tooker_registration_mark' => null,
            'returner_registration_mark' => null
        ]);

        $response = $this->withHeaders($authorizationHeader)
            ->getJson('/api/losts/materials');

        $response->assertStatus(200)
            ->assertJsonCount(5);
    }

    /** @test **/
    public function shouldListOnlyLostMaterialsThatWereNotReturnedToTheOwner()
    {
        $user = factory(User::class)->create();
        $token = resolve(JsonWebToken::class)->generateToken($user->toArray());
        $authorizationHeader = ['Authorization' => "Bearer $token"];

        factory(Material::class, 3)->create([
            'tooker_registration_mark' => null
        ]);

        $response = $this->withHeaders($authorizationHeader)
            ->getJson('/api/losts/materials?returned=false');

        $response->assertStatus(200)
            ->assertJsonCount(3);
    }

    /** @test **/
    public function shouldListOnlyLostMaterialsThatWereReturnedToTheOwner()
    {
        $user = factory(User::class)->create();
        $token = resolve(JsonWebToken::class)->generateToken($user->toArray());
        $authorizationHeader = ['Authorization' => "Bearer $token"];

        factory(Material::class, 2)->create();

        $response = $this->withHeaders($authorizationHeader)
            ->getJson('/api/losts/materials?returned=true');

        $response->assertStatus(200)
            ->assertJsonCount(2);
    }
}
