<?php

namespace Tests\Feature;

use App\Models\DNATest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckMutantTest extends TestCase
{
    use RefreshDatabase;

    public function mutantsDataSet()
    {
        return [
            '2 rows' => [
                "AAAA",
                "TTTT",
                "AGCT",
                "GGAT",
            ],
            '2 columns' => [
                "AGGC",
                "ACGA",
                "AGGT",
                "AAGG",
            ],
            '3 diagonals left to right' => [
                "ATGCTA",
                "GAGCGG",
                "CTAGCA",
                "TCGAGA",
                "TTCAGG",
                "TTACGG",
            ],
            '2 diagonals right to left' => [
                "ATGCTA",
                "GAGTGG",
                "CTTGCA",
                "TTGCGA",
                "TTCAGC",
                "TCACGG",
            ],
            '1 of each' => [
                "ATGCTA",
                "GAGTAG",
                "TATACA",
                "TAAAAA",
                "TTCAGC",
                "TCGCAG",
            ],
            'doc test' => [
                "ATGCGA",
                "CAGTGC",
                "TTATGT",
                "AGAAGG",
                "CCCCTA",
                "TCACTG",
            ],
        ];
    }

    /**
     * @dataProvider mutantsDataSet
     */
    public function test_is_mutant(...$dna)
    {
        $initialCount = DNATest::query()->count();
        $initialMutantsCount = DNATest::query()->isMutant()->count();

        $response = $this->post('/mutant', ["dna" => $dna]);

        $response->assertOk();
        $this->assertEquals(++$initialCount, DNATest::query()->count());
        $this->assertEquals(++$initialMutantsCount, DNATest::query()->isMutant()->count());
    }

    public function notMutantsDataSet()
    {
        return [
            '1x1' => [[
                "A",
            ]],
            '2x2' => [[
                "AT",
                "TT",
            ]],
            '3x3' => [[
                "AGC",
                "TGC",
                "TTG",
            ]],
            '4x3' => [[
                "TGC",
                "TGC",
                "TTG",
                "TTG",
            ]],
            '3x4' => [[
                "TGCG",
                "TGCC",
                "TTTT",
            ]],
            'nothing found' => [[
                "ATAA",
                "TTGT",
                "AGCT",
                "GGAT",
            ]],
            'Bad Chars' => [[
                "AXAA",
                "TTGT",
                "AGXT",
                "GGAT",
            ]],
            'doc Test' => [[
                "ATGCGA",
                "CAGTGC",
                "TTATTT",
                "AGACGG",
                "GCGTCA",
                "TCACTG",
            ]],
            'not array' => [12356],
            'null' => [null],
            'empty string' => [''],
            'empty array' => [[
                //
            ]],
            'empty arrays' => [[
                []
            ]],
            'mixed types' => [[
                "ATGCGA",
                ["B"],
                "ATGCGA",
                ["B"],
            ]],
            'bad lengths' => [[
                "ATGCGA",
                "ATGCAAG",
                "TTTCGA",
                "CTGCGA",
            ]],
        ];
    }

    /**
     * @dataProvider notMutantsDataSet
     */
    public function test_is_not_mutant($dna)
    {
        $initialCount = DNATest::count();
        $initialHumansCount = DNATest::isHuman()->count();

        $response = $this->post('/mutant', ["dna" => $dna]);

        $response->assertForbidden();
        $this->assertEquals(++$initialCount, DNATest::query()->count());
        $this->assertEquals(++$initialHumansCount, DNATest::query()->isHuman()->count());
    }
}
