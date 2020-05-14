<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LostMaterialsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function shouldCreateANewLostMaterial()
    {
        $body = [
            'nome' => 'Lost Material Test',
            'descricao' => 'Lost material only for test',
            'matriculaDeQuemEntregou' => '20161038060041'
        ];

        $response = $this->postJson('/api/materials/losts', $body);

        $response->assertStatus(201)
            ->assertJson([
                'nome' => $body['nome'],
                'descricao' => $body['descricao'],
                'matriculaDeQuemEntregou' => $body['matriculaDeQuemEntregou']
            ]);
    }

    /** @test */
    public function shouldCheckTypesOfInputsWhenForCreateANewLostMaterial()
    {
        $bodyWithNameTypeIncorrect = [
            'nome' => 123,
            'descricao' => 'Lost material only for test',
            'matriculaDeQuemEntregou' => '20161038060041'
        ];
        $response = $this->postJson('/api/materials/losts', $bodyWithNameTypeIncorrect);
        $response->assertStatus(422);

        $bodyWithDescriptionTypeIncorrect = [
            'nome' => 'Lost Material Test',
            'descricao' => 123,
            'matriculaDeQuemEntregou' => '20161038060041'
        ];
        $response = $this->postJson('/api/materials/losts', $bodyWithDescriptionTypeIncorrect);
        $response->assertStatus(422);

        $bodyWithMatriculaTypeIncorrect = [
            'nome' => 'Lost Material Test',
            'descricao' => 'Lost material only for test',
            'matriculaDeQuemEntregou' => 20161038060041
        ];
        $response = $this->postJson('/api/materials/losts', $bodyWithMatriculaTypeIncorrect);
        $response->assertStatus(422);
    }
}
