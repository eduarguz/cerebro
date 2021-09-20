<?php

namespace App;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class DNATest
{
    private Collection $dna;

    private array $sequences = ['AAAA', 'TTTT', 'GGGG', 'CCCC'];

    public function __construct(array $dna)
    {
        $this->setDNA($dna);
    }

    private function setDNA(array $dna): void
    {
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

    public function passes(): bool
    {
        $totalInRows = $this->checkRows();
        $totalInColumns = $this->checkColumns();
        $totalInDiagonalsFromRight = $this->checkDiagonalsFromRight();
        $totalInDiagonalsFromLeft = $this->checkDiagonalsFromLeft();

        $sum = $totalInRows + $totalInColumns + $totalInDiagonalsFromRight + $totalInDiagonalsFromLeft;

        return $sum > 1;
    }

    private function checkRows(): int
    {
        return $this->check($this->dna);
    }

    private function check(Collection $collection): int
    {
        return $collection
            ->map
            ->join('')
            ->sum(function ($line) {
                return collect($this->sequences)->sum(fn($sequence) => Str::substrCount($line, $sequence));
            });
    }

    private function checkColumns(): int
    {
        return $this->check($this->dna->transpose());
    }

    private function checkDiagonalsFromRight(): int
    {
        return $this->check($this->buildDiagonals($this->dna));
    }

    private function checkDiagonalsFromLeft(): int
    {
        $reversed = $this->dna->map->reverse()->map->values();

        return $this->check($this->buildDiagonals($reversed));
    }

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
