<?php

namespace App\Repositories\Quiz;

use App\DTOs\QuizDTO;
use App\Interface\Quiz\QuizRepositoryInterface;
use App\Models\Quiz;

class QuizRepository  implements QuizRepositoryInterface
{

    public function getAllQuiz() {}
    public function getQuizById($id) {}

    public function updateQuiz($id, QuizDTO $quizDTO) {}
    public function createQuiz(QuizDTO $quizDTO)
    {
        return Quiz::create([
            "question_title" => $quizDTO->getTitle(),
            "question_answer" => $quizDTO->getDescription(),
            "user_id" => $quizDTO->getUserId()
        ]);
    }
    public function deleteQuiz($id) {}
}
