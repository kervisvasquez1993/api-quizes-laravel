<?php

namespace App\Services\Quiz;

use App\DTOs\QuizDTO;
use App\Interface\Quiz\QuizRepositoryInterface;
use App\Models\Quiz;
use App\Services\Auth\AuthServices;
use Exception;


class QuizServices
{
    protected QuizRepositoryInterface $quizRepository;
    protected AuthServices $authServices;

    public function __construct(QuizRepositoryInterface $quizRepository, AuthServices $authServices)
    {
        $this->quizRepository = $quizRepository;
        $this->authServices = $authServices;
    }
    public function getAllQuizzes()
    {
        return $this->quizRepository->getAllQuiz();
    }

    public function questionForQuiz($quizId)
    {
        try {
            $quiz = $this->quizRepository->getQuizById($quizId);
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



    public function createQuiz(QuizDTO $quiz)
    {

        try {
            $quiz = $this->quizRepository->createQuiz($quiz);
            return [
                'success' => true,
                'data' => $quiz

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
            $quiz = $this->quizRepository->getQuizById($id);
            $updatedQuiz = $this->quizRepository->updateQuiz($quiz, $quizDTO);
            return ['success' => true, "data" => $updatedQuiz, 'message' => 'Record updated successfully'];
        } catch (\Exception $exception) {
            error_log($exception);
            return [
                'success' => false,
                'message' => $exception->getMessage()
            ];
        }
    }

    public function deletedQuiz($id)
    {
        try {
            $this->authServices->validateRole();
            $quiz = $this->quizRepository->getQuizById($id);
            $this->quizRepository->deletedQuiz($id);
            return ['success' => true, 'data' => $quiz];
        } catch (\Exception $exception) {
            return [
                'success' => false,
                'message' => $exception->getMessage()
            ];
        }
    }
}
