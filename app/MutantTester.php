<?php

namespace App;

use Illuminate\Support\Collection;

class MutantTester
{
    /**
     * @var \App\DNACollection
     */
    private DNACollection $dna;

    /**
     * @var int
     */
    private int $minOccurrences = 2;

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

        if ($dna->isEmpty()) {
            throw new \LogicException('DNA should not be empty');
        }

        $firstItem = $dna->first();

        if (!is_string($firstItem)) {
            throw new \LogicException('DNA should only contain string data');
        }

        $expectedLength = strlen($firstItem);

        if ($expectedLength < 4) {
            throw new \LogicException('Too little data to analyze DNA');
        }

        $dna->each(function ($row) use ($expectedLength) {
            if (!is_string($row)) {
                throw new \LogicException('DNA should only contain string data');
            }

            if (strlen($row) !== $expectedLength) {
                throw new \LengthException("Element's in DNA must have same length");
            }
        });

        $this->dna = new DNACollection($dna->map(fn($line) => collect(str_split($line))));
    }

    /**
     * @return bool
     */
    public function isMutant(): bool
    {
        $total = $this->checkRows();

        if ($this->isEnough($total)) {
            return true;
        }

        $total += $this->checkColumns();

        if ($this->isEnough($total)) {
            return true;
        }

        $total += $this->checkDiagonalsFromRight();

        if ($this->isEnough($total)) {
            return true;
        }

        $total += $this->checkDiagonalsFromLeft();

        return $this->isEnough($total);
    }

    /**
     * @param $total
     * @return bool
     */
    private function isEnough($total): bool
    {
        return $total >= $this->minOccurrences;
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
     * @param \App\DNACollection $collection
     * @param int $sumUntil
     * @return int
     */
    private function check(DNACollection $collection, int $sumUntil = 2): int
    {
        return $collection->map->join('')->sumSequencesUntil($sumUntil);
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
        return $this->check($this->dna->toDiagonals());
    }

    /**
     * @return int
     */
    private function checkDiagonalsFromLeft(): int
    {
        $reversed = $this->dna->map->reverse()->map->values();

        return $this->check($reversed->toDiagonals());
    }
}
