<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LostMaterials extends TestCase
{
    /**
     * @test
     */
    public function Example()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
