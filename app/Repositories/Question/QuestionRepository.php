<?php

namespace App\Repositories\Question;

use App\DTOs\QuestionDTO;
use App\Interface\Question\QuestionRepositoryInterface;
use App\Models\Question;

class QuestionRepository  implements QuestionRepositoryInterface
{
    public function createQuestion(QuestionDTO $questionDTO)
    {
        return Question::create([
            "question" => $questionDTO->getText(),
            "quiz_id" => $questionDTO->getQuizId(),
            "image" => $questionDTO->getImage(),
            "correct_answer" => $questionDTO->getCorrectAnswer(),
            "user_id" => $questionDTO->getUserId()
        ]);
    }
}
