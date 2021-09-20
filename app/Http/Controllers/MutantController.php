<?php

namespace App\Http\Controllers;

use App\DNATest;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MutantController extends Controller
{
    public function store(Request $request): \Illuminate\Http\Response
    {
        $dna = $request->input('dna');
        $dnaModel = \App\Models\DNATest::create(['dna' => $dna]);

        abort_if(!is_array($dna), Response::HTTP_FORBIDDEN);

        try {
            $dna = new DNATest($request->input('dna'));
            if ($dna->passes()) {
                $dnaModel->is_mutant = true;
                $dnaModel->save();
                $status = Response::HTTP_OK;
            } else {
                $status = Response::HTTP_FORBIDDEN;
            }

        } catch (\LogicException $ex) {
            $status = Response::HTTP_FORBIDDEN;
        }

        return response('', $status);
    }
}
