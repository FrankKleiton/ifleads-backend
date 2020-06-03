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
            'amount' => 2,
            'returner_registration_mark' => null,
            'tooker_registration_mark' => null
        ]);

        $body = [
            'tooker_id' => '20161038060041',
            'material_id' => $material->id
        ];

        $response = $this->withHeaders($authorizationHeader)
            ->postJson('api/loans', $body);

        $material->refresh();

        $response->assertStatus(201)
            ->assertJson([
                'user_id' => $user->id,
                'tooker_id' => $body['tooker_id'],
                'material_id' => $body['material_id']
            ]);

        $this->assertEquals(
            $material->amount, 1, 'The material amount should be decremented.'
        );
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
            'material_id' => $material->id
        ];

        $response = $this->withHeaders($authorizationHeader)
            ->postJson('api/loans', $body);

        $response->assertStatus(400)
            ->assertJsonFragment([
                'message' => sprintf('The material amount is in %d, only material in stock can be loaned!', $material->amount)
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
            'material_id' => $lost_material->id
        ];

        $response = $this->withHeaders($authorizationHeader)
            ->postJson('api/loans', $body);

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

        $loan = factory(Loan::class)->create();

        $response = $this->withHeaders($authorizationHeader)
            ->putJson("api/loans/$loan->id");

        $response->assertStatus(200);
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

        $response->assertStatus(400)
            ->assertJson([
                'message' => "The provided loan doesn't exists"
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
