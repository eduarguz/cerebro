<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class MutantController extends Controller
{
    public function store(Request $request)
    {
        $asLines = collect($request->input('dna'));
        $asArray = $asLines->map(fn($line) => collect(str_split($line)));

        $totalInRows = $this->findSequences($asArray);

        $transposeDNA = $asArray->transpose();
        $totalInColumns = $this->findSequences($transposeDNA);

        $diagonalRtl = $this->buildDiagonals($asArray);
        $totalInDiagonalRtl = $this->findSequences($diagonalRtl);

        $reversed = $asArray->map->reverse()->map->values();
        $diagonalLtr = $this->buildDiagonals($reversed);
        $totalInDiagonalLtr = $this->findSequences($diagonalLtr);

        if (($totalInRows + $totalInColumns + $totalInDiagonalRtl + $totalInDiagonalLtr) > 1) {
            return response('');
        }

        return response('', Response::HTTP_FORBIDDEN);
    }

    private function buildDiagonals(\Illuminate\Support\Collection $asArray)
    {
        if ($asArray->isEmpty()) {
            return new Collection();
        }

        $length = $asArray->count();
        $diags = [];

        for ($i = 0; $i < $length; $i++) {
            for ($j = 0; $j < $length; $j++) {
                $diags[$i + $j][] = $asArray[$i][$j];
            }
        }

        return collect($diags)->map(fn($items) => collect($items));
    }

    private function findSequences(Collection $asArray)
    {
        return $asArray
            ->map
            ->join('')
            ->sum(function ($line) {
                return collect(['AAAA', 'TTTT', 'GGGG', 'CCCC'])
                    ->sum(fn($sequence) => Str::substrCount($line, $sequence));
            });
    }
}
