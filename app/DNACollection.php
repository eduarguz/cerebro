<?php

namespace App;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class DNACollection extends Collection
{
    /**
     * @var array|string[]
     */
    private array $sequences = ['AAAA', 'TTTT', 'GGGG', 'CCCC'];

    /**
     * @param $max
     * @return int
     */
    public function sumSequencesUntil($max): int
    {
        $sum = 0;

        foreach ($this->items as $line) {
            foreach ($this->sequences as $sequence){
                $sum += Str::substrCount($line, $sequence);

                if ($sum >= $max) {
                    return $sum;
                }
            }
        }

        return $sum;
    }

    /**
     * @return \App\DNACollection
     */
    public function toDiagonals(): DNACollection
    {
        $length = $this->count();
        $diags = [];

        for ($i = 0; $i < $length; $i++) {
            for ($j = 0; $j < $length; $j++) {
                $diags[$i + $j][] = $this->items[$i][$j];
            }
        }

        return (new static($diags))->map(fn($items) => collect($items));
    }
}
