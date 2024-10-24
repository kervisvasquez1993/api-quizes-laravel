<?php

namespace App\Services\Question;

use App\DTOs\QuestionDTO;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Interface\Question\QuestionRepositoryInterface;
use App\Models\Question;
use Exception;

class QuestionServices
{
    protected QuestionRepositoryInterface $questionRepository;

    public function __construct(QuestionRepositoryInterface $questionRepository)
    {
        $this->questionRepository = $questionRepository;
    }


    public function findQuestionOrFail($id)
    {
        $question = Question::find($id);
        if (!$question) {
            $message = "No query results for model Student {$id}";
            throw new \Exception($message);
        }
        return $question;
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

    public function updateQuestion(QuestionDTO $questionDTO, $id)
    {
        try {
            $question = $this->findQuestionOrFail($id);
            $updateQuestion = $this->questionRepository->updateQuestion($question, $questionDTO);
            return ['success' => true, "data" => $updateQuestion, 'message' => 'Record updated successfully'];
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

    public function updateImage(?UploadedFile $file, int $questionId): ?string
    {
        $question = $this->findQuestionOrFail($questionId);

        if ($question->image) {
            $this->deleteFile($question->image);
        }
        if ($file) {
            $path = $file->store('questions', 'public');
            $question->image = Storage::url($path);
            $question->save();
            return $question->image;
        }
        return null;
    }

    private function deleteFile(string $filePath): void
    {
        $relativePath = str_replace('/storage/', '', $filePath);
        if (Storage::disk('public')->exists($relativePath)) {
            Storage::disk('public')->delete($relativePath);
        }
    }
}
