<?php

namespace App;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class MutantTester
{
    /**
     * @var \Illuminate\Support\Collection
     */
    private Collection $dna;

    /**
     * @var array|string[]
     */
    private array $sequences = ['AAAA', 'TTTT', 'GGGG', 'CCCC'];

    /**
     * @param $dna
     */
    public function __construct($dna)
    {
        $this->setDNA($dna);
    }

    /**
     * @param $dna
     */
    private function setDNA($dna): void
    {
        if (!is_array($dna)) {
            throw new \LogicException('DNA should contain array of strings');
        }

        $dna = collect($dna);

        if ($dna->isEmpty()){
            throw new \LogicException('DNA should not be empty');
        }

        $firstItem = $dna->first();

        if (!is_string($firstItem)){
            throw new \LogicException('DNA should only contain string data');
        }

        $expectedLength = strlen($firstItem);

        if ($expectedLength < 4){
            throw new \LogicException('Too little data to analyze DNA');
        }

        $dna->each(function ($row) use ($expectedLength){
            if (!is_string($row)){
                throw new \LogicException('DNA should only contain string data');
            }

            if (strlen($row) !== $expectedLength) {
                throw new \LengthException("Element's in DNA must have same length");
            }
        });

        $this->dna = $dna->map(fn($line) => collect(str_split($line)));
    }

    /**
     * @return bool
     */
    public function isMutant(): bool
    {
        $totalInRows = $this->checkRows();
        $totalInColumns = $this->checkColumns();
        $totalInDiagonalsFromRight = $this->checkDiagonalsFromRight();
        $totalInDiagonalsFromLeft = $this->checkDiagonalsFromLeft();

        $sum = $totalInRows + $totalInColumns + $totalInDiagonalsFromRight + $totalInDiagonalsFromLeft;

        return $sum > 1;
    }

    /**
     * @return bool
     */
    public function result(): bool
    {
        return $this->isMutant();
    }

    /**
     * @return int
     */
    private function checkRows(): int
    {
        return $this->check($this->dna);
    }

    /**
     * @param \Illuminate\Support\Collection $collection
     * @return int
     */
    private function check(Collection $collection): int
    {
        return $collection
            ->map
            ->join('')
            ->sum(function ($line) {
                return collect($this->sequences)->sum(fn($sequence) => Str::substrCount($line, $sequence));
            });
    }

    /**
     * @return int
     */
    private function checkColumns(): int
    {
        return $this->check($this->dna->transpose());
    }

    /**
     * @return int
     */
    private function checkDiagonalsFromRight(): int
    {
        return $this->check($this->buildDiagonals($this->dna));
    }

    /**
     * @return int
     */
    private function checkDiagonalsFromLeft(): int
    {
        $reversed = $this->dna->map->reverse()->map->values();

        return $this->check($this->buildDiagonals($reversed));
    }

    /**
     * @param \Illuminate\Support\Collection $collection
     * @return \Illuminate\Support\Collection
     */
    private function buildDiagonals(Collection $collection): Collection
    {
        if ($collection->isEmpty()) {
            return new Collection();
        }

        $length = $collection->count();
        $diags = [];

        for ($i = 0; $i < $length; $i++) {
            for ($j = 0; $j < $length; $j++) {
                $diags[$i + $j][] = $collection[$i][$j];
            }
        }

        return collect($diags)->map(fn($items) => collect($items));
    }
}
