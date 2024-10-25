<?php

namespace App\Interface\Question;

use App\DTOs\QuestionDTO;
use App\Models\Question;

interface QuestionRepositoryInterface
{
    public function createQuestion(QuestionDTO $questionDTO);
    public function updateQuestion(Question $question, QuestionDTO $questionDTO);
}
