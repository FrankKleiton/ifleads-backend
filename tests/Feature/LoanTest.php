<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Services\Auth\JsonWebToken;
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

        $material = factory(Material::class)->create([
            'amount' => 3,
            'returner_registration_mark' => null,
            'tooker_registration_mark' => null
        ]);
        $material_amount = $material->amount;

        $amount = 2;
        $body = [
            'tooker_id' => '20161038060041',
            'material_amount' => $amount,
        ];

        $response = $this->withHeaders($authorizationHeader)
            ->postJson("api/loans/materials/{$material->id}", $body);

        $material->refresh();

        $response->assertStatus(201)
            ->assertJson([
                'user_id' => $user->id,
                'tooker_id' => $body['tooker_id'],
                'material_id' => $material->id
            ]);

        $this->assertEquals(
            $material->amount,
            $material_amount - $amount,
            'The material amount must be decremented.'
        );
    }

    /** @test */
    public function shouldThrownAnErrorIfLoanAmountGreaterThanMaterialAmount()
    {
        $user = factory(User::class)->create();
        $token = resolve(JsonWebToken::class)->generateToken($user->toArray());
        $authorizationHeader = ['Authorization' => "Bearer $token"];

        $material = factory(Material::class)->create([
            'amount' => 3,
            'returner_registration_mark' => null,
            'tooker_registration_mark' => null
        ]);

        $body = [
            'tooker_id' => '2061038060002',
            'material_amount' => 5
        ];

        $response = $this->withHeaders($authorizationHeader)
            ->postJson("api/loans/materials/{$material->id}", $body);

        $response->assertStatus(400)
            ->assertJson([
                'message' => sprintf(
                    'The material amount %d is insuficient to do a loan.',
                    $material->amount
                ),
            ]);
    }

    /** @test */
    public function shouldThrowAnErrorIfThereIsNotMaterial()
    {
        $user = factory(User::class)->create();
        $token = resolve(JsonWebToken::class)->generateToken($user->toArray());
        $authorizationHeader = ['Authorization' => "Bearer $token"];

        $material = factory(Material::class)->create([
            'amount' => 0,
            'returner_registration_mark' => null,
            'tooker_registration_mark' => null
        ]);

        $body = [
            'tooker_id' => '20161038060002',
        ];

        $response = $this->withHeaders($authorizationHeader)
            ->postJson("api/loans/materials/{$material->id}", $body);

        $response->assertStatus(422)
            ->assertJson([
                'message' => 'The given data was invalid.'
            ]);
    }

    /** @test */
    public function shouldThrowAnErrorIfItIsLostMaterial()
    {
        $user = factory(User::class)->create();
        $token = resolve(JsonWebToken::class)->generateToken($user->toArray());
        $authorizationHeader = ['Authorization' => "Bearer $token"];

        $lost_material = factory(Material::class)->create([
            'returner_registration_mark' => '20161038060041',
        ]);

        $body = [
            'tooker_id' => '20161038060041',
            'material_amount' => 3
        ];

        $response = $this->withHeaders($authorizationHeader)
            ->postJson("api/loans/materials/{$lost_material->id}", $body);

        $response->assertStatus(403)
            ->assertJson([
                'message' => "Lost Materials can't be loan"
            ]);

    }

    /** @test */
    public function shouldUpdateALoan()
    {
        $user = factory(User::class)->create();
        $token = resolve(JsonWebToken::class)->generateToken($user->toArray());
        $authorizationHeader = ['Authorization' => "Bearer $token"];

        $loan = factory(Loan::class)->create([
            'material_amount' => 5
        ]);

        $loanMaterialAmount = $loan->material_amount;


        $material = $loan->material()->first();
        $materialAmount = $material->amount;

        $response = $this->withHeaders($authorizationHeader)
            ->putJson("api/loans/$loan->id");

        $material->refresh();

        $response->assertStatus(200);
        $this->assertEquals(
            $material->amount,
            $materialAmount + $loanMaterialAmount,
            'The material amount must be incremented'
        );
    }

    /** @test */
    public function shouldThrowAnErrorIfMaterialAlreadyReturned()
    {
        $user = factory(User::class)->create();
        $token = resolve(JsonWebToken::class)->generateToken($user->toArray());
        $authorizationHeader = ['Authorization' => "Bearer $token"];

        $loan = factory(Loan::class)->create([
            'return_time' => now()
        ]);

        $response = $this->withHeaders($authorizationHeader)
            ->putJson("api/loans/$loan->id");

        $response->assertStatus(400)
            ->assertJson([
                'message' => 'Material already returned'
            ]);
    }

    /** @test */
    public function shouldThrowAnErrorIfLoanDoesNotExists()
    {
        $user = factory(User::class)->create();
        $token = resolve(JsonWebToken::class)->generateToken($user->toArray());
        $authorizationHeader = ['Authorization' => "Bearer $token"];

        $nonexistentId = 10;

        $response = $this->withHeaders($authorizationHeader)
            ->putJson("api/loans/$nonexistentId");

        $response->assertStatus(404)
            ->assertJson([
                'message' => "No query results for model [App\\Loan] $nonexistentId"
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

        $this->assertSoftDeleted('loans', [ 'id' => $loan->id ]);
    }
}
