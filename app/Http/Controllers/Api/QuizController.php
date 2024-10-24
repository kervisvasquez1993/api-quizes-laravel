<?php

namespace App\Http\Controllers\Api;

use App\DTOs\QuizDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Quiz\StoreQuizRequest;
use App\Http\Requests\Quiz\UpdateQuizRequest;
use App\Services\Quiz\QuizServices;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    protected QuizServices $quizServices;
    public function __construct(QuizServices $quizServices)
    {
        $this->quizServices = $quizServices;
    }
    public function store(StoreQuizRequest $request)
    {
        $result = $this->quizServices->createQuiz(QuizDTO::fromRequest($request));
        if (!$result['success']) {
            return response()->json([
                'error' => $result['message']
            ], 422);
        }
        return response()->json($result['data'], status: 201);
    }

    public function update(UpdateQuizRequest $request, string $id)
    {
        $result = $this->quizServices->updateQuiz(QuizDTO::fromUpdateRequest($request), $id);
        if (!$result['success']) {
            return response()->json([
                'error' => $result['message']
            ], 422);
        }
        return response()->json($result['data'], status: 200);
    }
}
