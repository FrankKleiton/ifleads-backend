<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Services\Auth\JsonWebToken;

class MaterialTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function shouldReturnsAnArrayMaterials()
    {
        $user = factory(\App\User::class)->create();
        $token = resolve(JsonWebToken::class)->generateToken($user->toArray());
        $authorizationHeader = ['Authorization' => "Bearer $token"];

        $materials = factory(\App\Material::class, 3)->create();
        $response = $this->withHeaders($authorizationHeader)
            ->getJson('/api/materials');

        $response->assertStatus(200)
                ->assertJsonCount(3);
    }

     /** @test */
    public function shouldReturnsOneMaterial()
    {
        $user = factory(\App\User::class)->create();
        $token = resolve(JsonWebToken::class)->generateToken($user->toArray());
        $authorizationHeader = ['Authorization' => "Bearer $token"];

        $material = factory(\App\Material::class)->create([
            'nome' => 'Material de Teste'
        ]);
        $response = $this->withHeaders($authorizationHeader)
            ->getJson("/api/materials/{$material->id}");

        $response->assertStatus(200)
            ->assertJson([
                'nome' => 'Material de Teste'
            ]);
    }

    /** @test */
    public function shouldCreatesANewMaterial()
    {
        $user = factory(\App\User::class)->create();
        $token = resolve(JsonWebToken::class)->generateToken($user->toArray());
        $authorizationHeader = ['Authorization' => "Bearer $token"];

        $response = $this->withHeaders($authorizationHeader)
            ->postJson('/api/materials', [
                'nome' => 'Material1',
                'descricao' => 'Esse material é utilizado apenas para testes',
            ]);

        $response->assertStatus(201)
                ->assertJson([
                    'nome' => 'Material1',
                    'descricao' => 'Esse material é utilizado apenas para testes'
                ]);
    }

    /** @test */
    public function shouldUpdatesAMaterial()
    {
        $user = factory(\App\User::class)->create();
        $token = resolve(JsonWebToken::class)->generateToken($user->toArray());
        $authorizationHeader = ['Authorization' => "Bearer $token"];

        $material = factory(\App\Material::class)->create([
            'nome' => 'Material de Teste'
        ]);

        $response = $this->withHeaders($authorizationHeader)
            ->putJson("/api/materials/{$material->id}", [
                'nome' => 'Material de Teste Editado'
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'nome' => 'Material de Teste Editado'
            ]);
    }

    /** @test */
    public function shouldThrowAnErrorIfHadNoMaterialToBeUpdated()
    {
        $user = factory(\App\User::class)->create();
        $token = resolve(JsonWebToken::class)->generateToken($user->toArray());
        $authorizationHeader = ['Authorization' => "Bearer $token"];

        $unexistMaterialId = 10;

        $response = $this->withHeaders($authorizationHeader)
            ->putJson("/api/materials/{$unexistMaterialId}", [
                'nome' => 'Material de Teste Editado'
            ]);

        $response->assertStatus(400)
            ->assertExactJson([
                 'error' => "Material doesn't exists"
            ]);
    }

    /** @test */
    public function shouldDeletesAMaterial()
    {
        $user = factory(\App\User::class)->create();
        $token = resolve(JsonWebToken::class)->generateToken($user->toArray());
        $authorizationHeader = ['Authorization' => "Bearer $token"];

        $material = factory(\App\Material::class)->create([
            'nome' => 'Material de Teste'
        ]);

        $response = $this->withHeaders($authorizationHeader)
            ->deleteJson("/api/materials/{$material->id}");

        $response->assertStatus(200);

        $this->assertSoftDeleted('materiais', [
            'nome' => $material->nome
        ]);
    }

    /** @test */
    public function shouldThrowAnErrorIfHadNoMaterialToBeDeleted()
    {
        $user = factory(\App\User::class)->create();
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
