<?php

namespace App\Services\Quiz;

use App\DTOs\QuizDTO;
use App\Interface\Quiz\QuizRepositoryInterface;
use App\Models\Quiz;
use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class QuizServices
{
    protected QuizRepositoryInterface $quizRepository;

    public function __construct(QuizRepositoryInterface $quizRepository)
    {
        $this->quizRepository = $quizRepository;
    }

    public function findStudentOrFail($id)
    {
        $student = Quiz::find($id);
        if (!$student) {
            $message = "No query results for model Student {$id}";
            throw new \Exception($message);
        }
        return $student;
    }
    public function getAllQuizzes()
    {
        return $this->quizRepository->getAllQuiz();
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
            $quiz = $this->findStudentOrFail($id);
            $updatedQuiz = $this->quizRepository->updateQuiz($quiz, $quizDTO);
            return ['success' => true, "data" => $updatedQuiz, 'message' => 'Record updated successfully'];
        } catch (\Exception $exception) {
            return [
                'success' => false,
                'message' => $exception->getMessage()
            ];
        }
    }

    public function saveFile(?UploadedFile $file): ?string
    {
        if ($file) {
            $path = $file->store('quizzes', 'public');
            return Storage::url($path);
        }
        return null;
    }
}
