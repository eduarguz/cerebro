<?php

namespace Tests\Feature;

use App\Models\DNATest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StatsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function may_get_stats()
    {
        $this->getJson('/stats')
            ->assertOk()
            ->assertJson([
                'count_mutant_dna' => '0',
                'count_human_dna' => '0',
                'ratio' => '0.0'
            ]);

        DNATest::factory()
            ->mutant()
            ->count(22)
            ->create();

        DNATest::factory()
            ->human()
            ->count(30)
            ->create();

        $this->getJson('/stats')
            ->assertOk()
            ->assertJson([
                'count_mutant_dna' => '22',
                'count_human_dna' => '30',
                'ratio' => '0.73'
            ]);
    }
}
