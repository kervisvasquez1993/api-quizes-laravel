<?php

namespace App\Repositories\PlayerAnswer;

use App\DTOs\PlayerAnswerDTO;
use App\Interface\PlayerAnwer\PlayerAnswerRepositoryInterface;
use App\Models\PlayerAnswer;

class PlayerAnswerRepository  implements PlayerAnswerRepositoryInterface
{
    public function createPlayerAnswer(PlayerAnswerDTO $playerAnswerDTO)
    {
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
}
