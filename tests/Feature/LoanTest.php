<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;
use App\Material;
use App\Loan;

class LoanTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function shouldReturnACollectionOfLoans()
    {
        $user = factory(User::class)->create();
        $token = resolve(JsonWebToken::class)->generateToken($user->toArray());
        $authorizationHeader = ['Authorization' => "Bearer $token"];

        factory(Loan::class, 3)->create();

        $response = $this->withHeaders($authorizationHeader)
            ->getJson('api/loans');

        $response->assertStatus(200)->assertJsonCount(3);
    }

    /** @test */
    public function shouldCreateANewLoan()
    {
        $user = factory(User::class)->create();
        $token = resolve(JsonWebToken::class)->generateToken($user->toArray());
        $authorizationHeader = ['Authorization' => "Bearer $token"];

        $material = factory(Material::class)->create();

        $body = [
            'tooker_id' => '20161038060041',
            'material_id' => $material->id
        ];

        $response = $this->withHeaders($authorizationHeader)
            ->postJson('api/loans', $body);

        $response->assertStatus(201)
            ->assertJson([
                'user_id' => $user->id,
                'tooker_id' => $body['tooker_id'],
                'material_id' => $body['material_id']
            ]);
    }

    /** @test */
    public function shouldUpdateALoan()
    {
        $user = factory(User::class)->create();
        $token = resolve(JsonWebToken::class)->generateToken($user->toArray());
        $authorizationHeader = ['Authorization' => "Bearer $token"];

        $loan = factory(Loan::class)->create();

        $body = [
            'return_time' => date('c')
        ];

        $response = $this->withHeaders($authorizationHeader)
            ->putJson("api/loans/$loan->id", $body);

        $response->assertStatus(200)
            ->assertJson([
                'return_time' => $body['return_time']
            ]);
    }

    /** @test */
    public function shouldDeleteALoan()
    {
        $user = factory(User::class)->create();
        $token = resolve(JsonWebToken::class)->generateToken($user->toArray());
        $authorizationHeader = ['Authorization' => "Bearer $token"];

        $loan = factory(Loan::class)->create();

        $response = $this->withHeaders($authorizationHeader)
            ->deleteJson("api/loans/$loan->id");

        $response->assertStatus(200);

        $this->assertDeleted($table, [ 'id' => $loan->id ]);
    }
}
