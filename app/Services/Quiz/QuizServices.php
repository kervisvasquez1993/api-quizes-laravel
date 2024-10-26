<?php

namespace App\Services\Quiz;

use App\DTOs\QuizDTO;
use App\Interface\Quiz\QuizRepositoryInterface;
use App\Models\Quiz;
use Exception;


class QuizServices
{
    protected QuizRepositoryInterface $quizRepository;

    public function __construct(QuizRepositoryInterface $quizRepository)
    {
        $this->quizRepository = $quizRepository;
    }

    public function findQuizOrFail($id)
    {
        $student = Quiz::find($id);
        if (!$student) {
            $message = "No query results for Quiz {$id}";
            throw new \Exception($message);
        }
        return $student;
    }
    public function getAllQuizzes()
    {
        return $this->quizRepository->getAllQuiz();
    }

    public function questionForQuiz($quizId)
    {
        try {
            $quiz = $this->findQuizOrFail($quizId);
            return $this->quizRepository->questionForQuiz($quiz);
        } catch (\Exception $exception) {
            return [
                'success' => false,
                'message' => $exception->getMessage()
            ];
        }
    }

    public function getQuizById($id)
    {
        try {
            return $this->quizRepository->getQuizById($id);
        } catch (\Exception $exception) {
            return [
                'success' => false,
                'message' => $exception->getMessage()
            ];
        }
    }

    public function deletedQuiz($id)
    {
        try {
            $this->findQuizOrFail($id);
            $this->quizRepository->deletedQuiz($id);
            return ['success' => true, 'message' => 'Record deleted successfully'];
        } catch (\Exception $exception) {
            return [
                'success' => false,
                'message' => $exception->getMessage()
            ];
        }
    }

    public function createQuiz(QuizDTO $quiz)
    {

        try {
            $quiz = $this->quizRepository->createQuiz($quiz);
            return [
                'success' => true,
                'data' => [
                    'quiz' => $quiz
                ]
            ];
        } catch (Exception $exception) {
            return [
                'success' => false,
                'message' => $exception->getMessage()
            ];
        }
    }

    public function updateQuiz(QuizDTO $quizDTO, $id)
    {
        try {
            $quiz = $this->findQuizOrFail($id);
            $updatedQuiz = $this->quizRepository->updateQuiz($quiz, $quizDTO);
            return ['success' => true, "data" => $updatedQuiz, 'message' => 'Record updated successfully'];
        } catch (\Exception $exception) {
            return [
                'success' => false,
                'message' => $exception->getMessage()
            ];
        }
    }
}
