<?php

namespace App\Repositories\PlayerAnswer;

use App\DTOs\PlayerAnswerDTO;
use App\Interface\PlayerAnwer\PlayerAnswerRepositoryInterface;
use App\Models\PlayerAnswer;
use App\Models\User;

class PlayerAnswerRepository  implements PlayerAnswerRepositoryInterface
{
    public function createPlayerAnswer(PlayerAnswerDTO $playerAnswerDTO)
    {
        error_log($playerAnswerDTO->getGivenAnswer());
        return PlayerAnswer::create([
            "user_id" => $playerAnswerDTO->getUserId(),
            "question_id" => $playerAnswerDTO->getQuestionId(),
            "given_answer" => $playerAnswerDTO->getGivenAnswer(),
            "is_correct" => $playerAnswerDTO->getIsCorrect()
        ]);
    }
    public function findAnswerByUserAndQuestion(int $userId, int $questionId): ?PlayerAnswer
    {
        return PlayerAnswer::where('user_id', $userId)
            ->where('question_id', $questionId)
            ->first();
    }

    public function questionByUserAnswer($questionId)
    {
        return PlayerAnswer::where('question_id', $questionId)
            ->with('user') 
            ->get();
    }

    public function userOrdeByPoint(){
        return User::orderBy('points', 'desc')->get();
    }
}
