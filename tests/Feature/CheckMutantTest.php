<?php

namespace Tests\Feature;

use Tests\TestCase;

class CheckMutantTest extends TestCase
{
    public function test_is_mutant()
    {
        $response = $this->post('/mutant', ["dna" => [
            "ATGCGA",
            "CAGTGC",
            "TTATGT",
            "AGAAGG",
            "CCCCTA",
            "TCACTG",
        ]]);

        $response->assertOk();
    }

    public function test_is_not_mutant()
    {
        $response = $this->post('/mutant', ["dna" => [
            "ATGCGA",
            "CAGTGC",
            "TTATTT",
            "AGACGG",
            "GCGTCA",
            "TCACTG",
        ]]);

        $response->assertForbidden();
    }
}
