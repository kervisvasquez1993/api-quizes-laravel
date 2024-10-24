<?php

namespace App\Services\Question;

use App\DTOs\QuestionDTO;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Interface\Question\QuestionRepositoryInterface;
use Exception;

class QuestionServices
{
    protected QuestionRepositoryInterface $questionRepository;

    public function __construct(QuestionRepositoryInterface $questionRepository)
    {
        $this->questionRepository = $questionRepository;
    }

    public function createQuestion(QuestionDTO $questionDTO)
    {
        try {
            $questionForQuiz = $this->questionRepository->createQuestion($questionDTO);
            return [
                'success' => true,
                'data' =>  $questionForQuiz
            ];
        } catch (Exception $exception) {
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
