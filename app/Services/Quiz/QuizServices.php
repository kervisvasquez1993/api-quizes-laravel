<?php

namespace App\Services\Quiz;

use App\DTOs\QuizDTO;
use App\Interface\Quiz\QuizRepositoryInterface;
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
        } catch (Exception $ex) {
            return [
                'success' => false,
                'message' => $ex->getMessage()
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
