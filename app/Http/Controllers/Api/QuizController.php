<?php

namespace App\Http\Controllers\Api;

use App\DTOs\QuizDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Quiz\StoreQuizRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuizController extends Controller
{
    public function store(StoreQuizRequest $request)
    {
        $userId = Auth::user()->id;
        $quizDTO = QuizDTO::fromRequest($request, $userId);
        return response()->json($quizDTO->toArray(), 201);
    }
}
