<?php

namespace App\Repositories\Question;

use App\DTOs\QuestionDTO;
use App\Interface\Question\QuestionRepositoryInterface;
use App\Models\Question;

class QuestionRepository  implements QuestionRepositoryInterface
{

    public function findQuestionById($id){
        $question = Question::with('quiz')->find($id);
        if (!$question) {
            $message = "No query results for Question {$id}";
            throw new \Exception($message);
        }
        return $question;
    }
    public function createQuestion(QuestionDTO $questionDTO): Question
    {
        return Question::create([
            "question" => $questionDTO->getText(),
            "quiz_id" => $questionDTO->getQuizId(),
            "image" => $questionDTO->getImage(),
            "correct_answer" => $questionDTO->getCorrectAnswer(),
            "user_id" => $questionDTO->getUserId()
        ]);
    }
    public function updateQuestion(Question $question, QuestionDTO $questionDTO): Question
    {
        $question->update([
            "quiz_id" => $questionDTO->getQuizId(),
            "question" => $questionDTO->getText(),
            "correct_answer" => $questionDTO->getCorrectAnswer()
        ]);
        return $question;
    }

    public function updateImage(Question $question, string $imagePath): Question
    {
        $question->update([
            "image" => $imagePath
        ]);
        return $question;
    }
    public function deletedQuestion($id){
        return Question::destroy($id);
    }
}
