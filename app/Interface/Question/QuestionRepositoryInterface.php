<?php

namespace App\Interface\Question;

use App\DTOs\QuestionDTO;
use App\Models\Question;

interface QuestionRepositoryInterface
{
    public function findQuestionById(string $id);
    public function createQuestion(QuestionDTO $questionDTO);
    public function updateQuestion(Question $question, QuestionDTO $questionDTO);
    public function updateImage(Question $question, string $imagePath): Question;
    public function deletedQuestion(int $id);
}
