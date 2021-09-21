<?php

namespace Database\Factories;

use App\Models\DNATest;
use Illuminate\Database\Eloquent\Factories\Factory;

class DNATestFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = DNATest::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'dna' => [
                "AAAA",
                "TTTT",
                "AGCT",
                "GGAT",
            ]
        ];
    }

    public function mutant()
    {
        return $this->state(fn() => ['is_mutant' => true]);
    }

    public function human()
    {
        return $this->state(fn() => ['is_mutant' => false]);
    }
}
