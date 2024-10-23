<?php

namespace App\Interface\Quiz;

use App\DTOs\QuizDTO;

interface QuizRepositoryInterface
{
    public function createQuiz(QuizDTO $quiz);

}
