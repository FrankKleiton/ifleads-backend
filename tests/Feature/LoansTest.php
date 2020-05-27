<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoansTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function shouldReturnACollectionOfLoans()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    /** @test */
    public function shouldCreateANewLoan()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    /** @test */
    public function shouldUpdateALoan()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    /** @test */
    public function shouldDeleteANewLoan()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
