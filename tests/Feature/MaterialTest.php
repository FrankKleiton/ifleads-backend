<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Services\Auth\JsonWebToken;
use App\User;
use App\Material;

class MaterialTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function shouldReturnsAListOfMaterials()
    {
        $user = factory(User::class)->create();
        $token = resolve(JsonWebToken::class)->generateToken($user->toArray());
        $authorizationHeader = ['Authorization' => "Bearer $token"];

        $materials = factory(Material::class, 3)->create([
            'amount' => 2,
            'returner_registration_mark' => null,
            'tooker_registration_mark' => null
        ]);

        $response = $this->withHeaders($authorizationHeader)
            ->getJson('/api/materials');

        $response->assertStatus(200)->assertJsonCount(3);
    }

     /** @test */
    public function shouldReturnsOneMaterial()
    {
        $user = factory(User::class)->create();
        $token = resolve(JsonWebToken::class)->generateToken($user->toArray());
        $authorizationHeader = ['Authorization' => "Bearer $token"];

        $material = factory(Material::class)->create([
            'name' => 'Material of Test'
        ]);

        $response = $this->withHeaders($authorizationHeader)
            ->getJson("/api/materials/{$material->id}");

        $response->assertStatus(200)
            ->assertJson([
                'name' => $material->name
            ]);
    }

    /** @test */
    public function shouldCreatesANewMaterial()
    {
        $user = factory(User::class)->create();
        $token = resolve(JsonWebToken::class)->generateToken($user->toArray());
        $authorizationHeader = ['Authorization' => "Bearer $token"];

        $body = [
            'name' => 'Material',
            'description' => 'It is only to environment of test',
        ];

        $response = $this->withHeaders($authorizationHeader)
            ->postJson('/api/materials', $body);

        $response->dump();

        $response->assertStatus(201)
            ->assertJson([
                'name' => $body['name'],
                'description' => $body['description']
            ]);
    }

    /** @test */
    public function shouldUpdatesAMaterial()
    {
        $user = factory(User::class)->create();
        $token = resolve(JsonWebToken::class)->generateToken($user->toArray());
        $authorizationHeader = ['Authorization' => "Bearer $token"];

        $material = factory(Material::class)->create([
            'name' => 'Material of Test'
        ]);

        $response = $this->withHeaders($authorizationHeader)
            ->putJson("/api/materials/{$material->id}", [
                'name' => 'Material of Test Updated'
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'name' => 'Material of Test Updated'
            ]);
    }

    /** @test */
    public function shouldThrowAnErrorIfHadNoMaterialToBeUpdated()
    {
        $user = factory(User::class)->create();
        $token = resolve(JsonWebToken::class)->generateToken($user->toArray());
        $authorizationHeader = ['Authorization' => "Bearer $token"];

        $unexistMaterialId = 10;

        $response = $this->withHeaders($authorizationHeader)
            ->putJson("/api/materials/{$unexistMaterialId}", [
                'name' => 'Material of Test Updated'
            ]);

        $response->assertStatus(404)
            ->assertJson([
                 'message' => "No query results for model [App\Material] $unexistMaterialId"
            ]);
    }

    /** @test */
    public function shouldDeletesAMaterial()
    {
        $user = factory(User::class)->create();
        $token = resolve(JsonWebToken::class)->generateToken($user->toArray());
        $authorizationHeader = ['Authorization' => "Bearer $token"];

        $material = factory(Material::class)->create([
            'name' => 'Material of Test'
        ]);

        $response = $this->withHeaders($authorizationHeader)
            ->deleteJson("/api/materials/{$material->id}");

        $response->assertStatus(200);

        $this->assertSoftDeleted('materials', [
            'name' => $material->name
        ]);
    }

    /** @test */
    public function shouldThrowAnErrorIfHadNoMaterialToBeDeleted()
    {
        $user = factory(User::class)->create();
        $token = resolve(JsonWebToken::class)->generateToken($user->toArray());
        $authorizationHeader = ['Authorization' => "Bearer $token"];

        $unexistMaterialId = 10;

        $response = $this->withHeaders($authorizationHeader)
            ->deleteJson("/api/materials/{$unexistMaterialId}");

        $response->assertStatus(404)
            ->assertJson([
                 'message' => "No query results for model [App\Material] $unexistMaterialId"
            ]);
    }

    /** @test */
    public function shoudReturnsOnlyMaterialsThatCanBeLoanded()
    {
        $user = factory(User::class)->create();
        $token = resolve(JsonWebToken::class)->generateToken($user->toArray());
        $authorizationHeader = ['Authorization' => "Bearer $token"];

        // lost material
        factory(Material::class)->create([
            'amount' => 2,
            'returner_registration_mark' => '20161038060041'
        ]);

        // normal materials
        factory(Material::class, 2)->create([
            'amount' => 2,
            'returner_registration_mark' => null,
            'tooker_registration_mark' => null
        ]);

        $response = $this->withHeaders($authorizationHeader)
            ->getJson('/api/materials');

        $response->assertStatus(200)
            ->assertJsonCount(2);
    }

    /** @test */
    public function shouldThrowErrorIfTryToCreateDuplicatedMaterial()
    {
        $user = factory(User::class)->create();
        $token = resolve(JsonWebToken::class)->generateToken($user->toArray());
        $authorizationHeader = ['Authorization' => "Bearer $token"];

        $body = [
            'name' => 'Caixa de Som',
            'description' => 'Caixa de som estéreo.',
            'returner_registration_mark' => null,
            'tooker_registration_mark' => null
        ];

        $material = factory(Material::class)->create($body);

        $body['returner_registration_mark'] = '20161038060002';

        $lostMaterial = factory(Material::class)->create($body);

        $response = $this->withHeaders($authorizationHeader)
            ->postJson('/api/materials', $body);

        $response->assertStatus(400)
            ->assertJsonFragment([
            'message' => 'The material already exists. Insert a valid material, please.'
        ]);
    }
}
