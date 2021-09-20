<?php

namespace App\Http\Controllers;

use App\Models\DNATest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StatsController extends Controller
{
    public function index(): JsonResponse
    {
        $mutants = DNATest::query()->isMutant()->count();
        $humans = DNATest::query()->isHuman()->count();

        if ($humans === 0){
            $ratio = 0;
        } else {
            $ratio = round($mutants/$humans, 2);
        }

        return response()->json([
            'count_mutant_dna' => $mutants,
            'count_human_dna' => $humans,
            'ratio' => (float) $ratio,
        ]);
    }
}
