<?php

namespace Tests\Feature;

use App\Models\DNATest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StatsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function may_get_empty_stats()
    {
        $this->getJson('/stats')
            ->assertOk()
            ->assertJson([
                'count_mutant_dna' => '0',
                'count_human_dna' => '0',
                'ratio' => '0.0'
            ]);
    }

    /** @test */
    public function may_get_some_stats()
    {
        DNATest::factory()
            ->mutant()
            ->count(40)
            ->create();

        DNATest::factory()
            ->human()
            ->count(100)
            ->create();

        $this->getJson('/stats')
            ->assertOk()
            ->assertJson([
                'count_mutant_dna' => '40',
                'count_human_dna' => '100',
                'ratio' => '0.4'
            ]);
    }
}
