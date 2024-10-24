<?php

namespace App\Interface\Question;

use App\DTOs\QuestionDTO;

interface QuestionRepositoryInterface
{
    public function createQuestion(QuestionDTO $questionDTO);
}
