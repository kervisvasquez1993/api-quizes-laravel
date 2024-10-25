<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PlayerAnswer\StorePlayerAnswerRequest;
use App\Services\PlayerAnwer\PlayerAnswerServices;
use Illuminate\Http\Request;

class PlayerAnswerController extends Controller
{

    protected PlayerAnswerServices $playerAnswerServices;
    public function __construct(PlayerAnswerServices $playerAnswerServices)
    {
        $this->playerAnswerServices = $playerAnswerServices;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePlayerAnswerRequest $request, $questionsId)
    {
        $result = $this->playerAnswerServices->playerAnswerByQuestion($request, $questionsId);
        if (!$result['success']) {
            return response()->json([
                'error' => $result['message']
            ], 422);
        }
        return response()->json($result['data'], status: 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
