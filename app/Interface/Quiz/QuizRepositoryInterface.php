<?php

namespace App\Interface\Quiz;

use App\DTOs\QuizDTO;
use App\Models\Quiz;

interface QuizRepositoryInterface
{
    public function getAllQuiz();
    public function getQuizById($id);
    public function createQuiz(QuizDTO $quiz);
    public function updateQuiz(Quiz $quiz, QuizDTO $updateQuiz);
    public function deletedQuiz($id);
    public function questionForQuiz(Quiz $quiz);
}
