<?php

namespace App\Http\Controllers;

use App\Models\DNATest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class StatsController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): JsonResponse
    {
        $stats = Cache::remember('stats', now()->addSeconds(5), function (){
            $mutants = DNATest::query()->isMutant()->count();
            $humans = DNATest::query()->isHuman()->count();
            $ratio = $humans === 0 ? 0 : round($mutants / $humans, 2);

            return [
                'count_mutant_dna' => $mutants,
                'count_human_dna' => $humans,
                'ratio' => (float) $ratio,
            ];
        });

        return response()->json($stats);
    }
}
