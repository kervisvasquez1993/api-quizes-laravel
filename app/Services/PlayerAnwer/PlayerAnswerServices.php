<?php

namespace App\Services\PlayerAnwer;

use App\DTOs\PlayerAnswerDTO;
use App\Interface\PlayerAnwer\PlayerAnswerRepositoryInterface;
use App\Models\Question;
use App\Services\Question\QuestionServices;
use Illuminate\Support\Facades\Auth;

class PlayerAnswerServices
{
    protected PlayerAnswerRepositoryInterface $playerAnswerRepository;
    protected QuestionServices $questionServices;


    public function __construct(PlayerAnswerRepositoryInterface $playerAnswerRepository, QuestionServices $questionServices)
    {
        $this->playerAnswerRepository = $playerAnswerRepository;
        $this->questionServices = $questionServices;
    }

    public function hasUserAnsweredQuestion(int $userId, int $questionId): bool
    {
        return $this->playerAnswerRepository->findAnswerByUserAndQuestion($userId, $questionId) !== null;
    }

    public function myAnswer(){
       return Auth::user()->playerAnswer;
    }
    public function playerAnswerByQuestion($request, int $question)
    {
        try {
            $userId = Auth::id();
            if ($this->hasUserAnsweredQuestion($userId, $question)) {
                return [
                    'success' => false,
                    'message' => 'You have already answered this question.'
                ];
            }
            $question = $this->questionServices->findQuestionOrFail($question);
            $isCorrect = (int) $question->correct_answer === (int) $request->given_answer;
            $playerAnswer = $this->playerAnswerRepository->createPlayerAnswer(PlayerAnswerDTO::fromRequest($request, $question->id, $isCorrect));
            return [
                'success' => true,
                'data' =>  $playerAnswer
            ];
        } catch (\Exception $exception) {
            return [
                'success' => false,
                'message' => $exception->getMessage()
            ];
        }
    }
}
