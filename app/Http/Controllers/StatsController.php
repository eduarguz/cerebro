<?php

namespace App\Http\Controllers;

use App\Models\DNATest;
use Illuminate\Http\JsonResponse;

class StatsController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): JsonResponse
    {
        $mutants = DNATest::query()->isMutant()->count();
        $humans = DNATest::query()->isHuman()->count();

        $ratio = $humans === 0 ? 0 : round($mutants / $humans, 2);

        return response()->json([
            'count_mutant_dna' => $mutants,
            'count_human_dna' => $humans,
            'ratio' => (float) $ratio,
        ]);
    }
}
