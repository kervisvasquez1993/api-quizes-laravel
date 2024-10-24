<?php

namespace App\Http\Controllers\Api;

use App\DTOs\QuestionDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Question\StoreQuestionRequest;
use App\Services\Question\QuestionServices;
use App\Services\Quiz\QuizServices;
use Exception;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    protected QuizServices $quizServices;
    protected QuestionServices $questionServices;

    public function __construct(QuizServices $quizServices, QuestionServices $questionServices)
    {
        $this->quizServices = $quizServices;
        $this->questionServices = $questionServices;
    }
    public function store(StoreQuestionRequest $request, $quizId)
    {

        try {
            $quiz = $this->quizServices->findQuizOrFail($quizId);
            $imgFile = $this->questionServices->saveFile($request->image);
            $result = $this->questionServices->createQuestion(QuestionDTO::fromRequest($request, $quiz->id, $imgFile));
            if (!$result['success']) {
                return response()->json([
                    'error' => $result['message']
                ], 422);
            }
            return response()->json($result['data'], status: 201);
        } catch (Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage()
            ], 422);
        }
    }
}
