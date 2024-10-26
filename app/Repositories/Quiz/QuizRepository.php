<?php

namespace App\Repositories\Quiz;

use App\DTOs\QuizDTO;
use App\Interface\Quiz\QuizRepositoryInterface;
use App\Models\Quiz;

class QuizRepository  implements QuizRepositoryInterface
{

    public function getAllQuiz()
    {
        return Quiz::all();
    }
    public function getQuizById($id)
    {
        $quiz = Quiz::find($id);
        if (!$quiz) {
            throw new \Exception("No results found for Quiz with ID {$id}");
        }
        return $quiz;
    }


    public function updateQuiz(Quiz $quiz, QuizDTO $quizDTO)
    {
        $quiz->update([
            "title" => $quizDTO->getTitle(),
            "description" => $quizDTO->getDescription()
        ]);
        return $quiz;
    }
    public function createQuiz(QuizDTO $quizDTO)
    {
        return Quiz::create([
            "title" => $quizDTO->getTitle(),
            "description" => $quizDTO->getDescription(),
            "user_id" => $quizDTO->getUserId()
        ]);
    }
   
    public function questionForQuiz(Quiz $quiz)
    {
        $quiz->load('questions');
        return $quiz;
    }

    public function deletedQuiz($id)
    {
        return Quiz::destroy($id);
    }
}
