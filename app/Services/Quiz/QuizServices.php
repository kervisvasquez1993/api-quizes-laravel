<?php

namespace App\Services\Quiz;

use App\DTOs\QuizDTO;
use App\Interface\Quiz\QuizRepositoryInterface;

class QuizServices
{
    protected QuizRepositoryInterface $quizRepository;

    public function __construct(QuizRepositoryInterface $quizRepository)
    {
        $this->quizRepository = $quizRepository;
    }

    public function createQuiz(QuizDTO $quiz)
    {
        $quiz = $this->quizRepository->createQuiz($quiz);
    }
}
