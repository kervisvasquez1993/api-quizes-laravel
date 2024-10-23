<?php

namespace App\Interface\Quiz;

use App\DTOs\QuizDTO;

interface QuizRepositoryInterface
{
    public function getAllQuiz();
    public function getQuizById($id);
    public function createQuiz(QuizDTO $quiz);
    public function updateQuiz($id, QuizDTO $updateQuiz);
    public function deleteQuiz($id);
}
