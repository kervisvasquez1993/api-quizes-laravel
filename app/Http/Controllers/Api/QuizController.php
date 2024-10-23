<?php

namespace App\Http\Controllers\Api;

use App\DTOs\QuizDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Quiz\StoreQuizRequest;
use App\Services\Quiz\QuizServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuizController extends Controller
{
    protected QuizServices $quizServices;
    public function __construct(QuizServices $quizServices)
    {
        $this->quizServices = $quizServices;
    }
    public function store(StoreQuizRequest $request)
    {
        $userId = Auth::user()->id;
        $url_img = $this->quizServices->saveFile($request->file('img'));
        $result = $this->quizServices->createQuiz(QuizDTO::fromRequest($request, $userId, $url_img));
        if (!$result['success']) {
            return response()->json([
                'error' => $result['message']
            ], 422);
        }
        return response()->json($result['data'], status: 201);
    }
}
