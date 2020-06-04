<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Services\Auth\JsonWebToken;
use App\User;

class MaterialTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function shouldReturnsAnArrayMaterials()
    {
        $user = factory(User::class)->create();
        $token = resolve(JsonWebToken::class)->generateToken($user->toArray());
        $authorizationHeader = ['Authorization' => "Bearer $token"];

        $materials = factory(\App\Material::class, 3)->create();
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

        $material = factory(\App\Material::class)->create([
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

        $material = factory(\App\Material::class)->create([
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

        $response->assertStatus(400)
            ->assertExactJson([
                 'error' => "Material doesn't exists"
            ]);
    }

    /** @test */
    public function shouldDeleteAMaterialIfAmountZero()
    {
        $user = factory(User::class)->create();
        $token = resolve(JsonWebToken::class)->generateToken($user->toArray());
        $authorizationHeader = ['Authorization' => "Bearer $token"];

        $material = factory(\App\Material::class)->create([
            'amount' => 0,
            'name' => 'Material of Test'
        ]);

        $response = $this->withHeaders($authorizationHeader)
            ->deleteJson("/api/materials/{$material->id}");

        $response->assertStatus(204);

        $this->assertSoftDeleted('materials', [
            'name' => $material->name
        ]);
    }

    /** @test */
    public function shouldDeleteAMaterialIfAmountGreaterThanZero()
    {
        $user = factory(User::class)->create();
        $token = resolve(JsonWebToken::class)->generateToken($user->toArray());
        $authorizationHeader = ['Authorization' => "Bearer $token"];

        $amount = 2;
        $material = factory(\App\Material::class)->create([
            'name' => 'Material of Test',
            'amount' => $amount
        ]);

        $response = $this->withHeaders($authorizationHeader)
            ->deleteJson("/api/materials/{$material->id}");

        $response->assertStatus(200);

        $material->refresh();

        $this->assertEquals(
            --$amount,
            $material->amount,
            'The material amount must be decremented.'
        );
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

        $response->assertStatus(400)
            ->assertExactJson([
                'error' => "Material doesn't exists"
            ]);
    }
}
