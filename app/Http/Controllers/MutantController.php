<?php

namespace App\Http\Controllers;

use App\Models\DNATest;
use App\MutantTester;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MutantController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request): \Illuminate\Http\Response
    {
        $record = DNATest::createNew($request->input('dna'));

        try {
            $mutantTest = new MutantTester($request->input('dna'));
        } catch (\LogicException $ex) {
            return $this->responseAsHuman();
        }

        $record->updateIsMutant($mutantTest->result());

        return $record->isMutant() ? $this->responseAsMutant() : $this->responseAsHuman();
    }

    /**
     * @return \Illuminate\Http\Response
     */
    private function responseAsMutant(): \Illuminate\Http\Response
    {
        return response('', Response::HTTP_OK);
    }

    /**
     * @return \Illuminate\Http\Response
     */
    private function responseAsHuman(): \Illuminate\Http\Response
    {
        return response('', Response::HTTP_FORBIDDEN);
    }
}
