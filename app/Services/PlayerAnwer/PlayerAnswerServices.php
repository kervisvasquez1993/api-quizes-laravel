<?php

namespace App\Services\PlayerAnwer;

use App\DTOs\PlayerAnswerDTO;
use App\Interface\PlayerAnwer\PlayerAnswerRepositoryInterface;
use App\Models\Question;
use App\Models\User;
use App\Services\Auth\AuthServices;
use App\Services\Question\QuestionServices;
use Exception;
use Illuminate\Support\Facades\Auth;

class PlayerAnswerServices
{
    protected PlayerAnswerRepositoryInterface $playerAnswerRepository;
    protected QuestionServices $questionServices;
    protected AuthServices $authServices;


    public function __construct(PlayerAnswerRepositoryInterface $playerAnswerRepository, QuestionServices $questionServices, AuthServices $authServices)
    {
        $this->playerAnswerRepository = $playerAnswerRepository;
        $this->questionServices = $questionServices;
        $this->authServices = $authServices;
    }

    public function hasUserAnsweredQuestion(int $userId, int $questionId): bool
    {
        return $this->playerAnswerRepository->findAnswerByUserAndQuestion($userId, $questionId) !== null;
    }

    public function myAnswer()
    {
        return Auth::user()->playerAnswer;
    }

    public function getUserAnswersById($id)
    {
        $data = User::find($id);
        if (!$data) {
            $message = "No query results for User {$id}";
            throw new \Exception($message);
        }
        return $data->playerAnswer;
    }

    public function getQuestionAnswersById($questionId)
    {
        try {
            $question = $this->questionServices->findQuestionOrFail($questionId);
            $data = $this->playerAnswerRepository->questionByUserAnswer($question->id);
            return [
                'success' => true,
                'data' => $data,
                "statusCode" => "200"
            ];
        } catch (Exception $exception) {
            return [
                'success' => false,
                'message' => $exception->getMessage(),
            ];
        }
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
            if ($isCorrect) {
                $this->authServices->updateUserPoints($userId, 10);  
            }
    
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
