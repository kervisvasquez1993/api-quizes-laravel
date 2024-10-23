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
            "question_title" => $quizDTO->getQuestionTitle(),
            "question_answer" => $quizDTO->getQuestionAnswer(),
            "user_id" => $quizDTO->getUserId(),
            "img" => $quizDTO->getImg()
        ]);
    }
    public function deleteQuiz($id) {}
}
