<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MaterialTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function shouldReturnsAnArrayMaterials()
    {
        $materials = factory(\App\Material::class, 3)->create();
        $response = $this->getJson('/api/materials');

        $response->assertStatus(200)
                ->assertJsonCount(3);
    }

     /** @test */
    public function shouldReturnsOneMaterial()
    {
        $material = factory(\App\Material::class)->create([
            'nome' => 'Material de Teste'
        ]);
        $response = $this->getJson("/api/materials/{$material->id}");

        $response->assertStatus(200)
            ->assertJson([
                'nome' => 'Material de Teste'
            ]);
    }

    /** @test */
    public function shouldCreatesANewMaterial()
    {
        $user = factory(\App\User::class)->create();

        $response = $this->postJson('/api/materials', [
            'nome' => 'Material1',
            'descricao' => 'Esse material é utilizado apenas para testes',
            'emprestado' => false,
            'usuario_id' => $user->id
        ]);

        $response->assertStatus(201)
                ->assertJson([
                    'nome' => 'Material1',
                    'descricao' => 'Esse material é utilizado apenas para testes'
                ]);
    }

    /** @test */
    public function shouldThrowAnErrorIfUserNotExists()
    {
        $unexistUserId = 1;

        $response = $this->postJson('/api/materials', [
            'nome' => 'Material1',
            'descricao' => 'Esse material é utilizado apenas para testes',
            'emprestado' => false,
            'usuario_id' => $unexistUserId
        ]);

        $response->assertStatus(400)
            ->assertExactJson([
                'error' => "User doesn't exists"
            ]);
    }

    /** @test */
    public function shouldUpdatesAMaterial()
    {
        $material = factory(\App\Material::class)->create([
            'nome' => 'Material de Teste'
        ]);

        $response = $this->putJson("/api/materials/{$material->id}", [
            'nome' => 'Material de Teste Editado'
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'nome' => 'Material de Test Editado'
            ]);
    }

    /** @test */
    public function shouldThrowAnErrorIfHadNoMaterialToBeUpdated()
    {
        $unexistMaterialId = 10;

        $response = $this->putJson("/api/materials/{$unexistMaterialId}", [
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
        $material = factory(\App\Material::class)->create([
            'nome' => 'Material de Teste'
        ]);

        $response = $this->deleteJson("/api/materials/{$material->id}");

        $response->assertStatus(200);

        $this->assertSoftDeleted('materiais', [
            'nome' => $material->nome
        ]);
    }

    /** @test */
    public function shouldThrowAnErrorIfHadNoMaterialToBeDeleted()
    {
        $unexistMaterialId = 10;

        $response = $this->deleteJson("/api/materials/{$unexistMaterialId}");

        $response->assertStatus(400)
            ->assertExactJson([
                'error' => "User doesn't exists"
            ]);
    }
}
