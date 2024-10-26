<?php

namespace App\Services\Question;

use App\DTOs\QuestionDTO;
use App\Http\Requests\Question\StoreQuestionRequest;
use App\Interface\Quiz\QuizRepositoryInterface;
use App\Services\Auth\AuthServices;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Interface\Question\QuestionRepositoryInterface;
use App\Models\Question;
use App\Models\Quiz;
use Exception;

class QuestionServices
{
    protected QuestionRepositoryInterface $questionRepository;
    protected QuizRepositoryInterface $quizRepository;
    protected AuthServices $authServices;

    public function __construct(QuestionRepositoryInterface $questionRepository, QuizRepositoryInterface $quizRepository, AuthServices $authServices)
    {
        $this->questionRepository = $questionRepository;
        $this->quizRepository = $quizRepository;
        $this->authServices = $authServices;
    }


    public function findQuestionOrFail($id)
    {
        $question = Question::find($id);
        if (!$question) {
            $message = "No query results for Question {$id}";
            throw new \Exception($message);
        }
        return $question;
    }

    public function questionForQuiz(Quiz $quiz) {}

    public function createQuestionWithQuiz(StoreQuestionRequest $request, $quizId)
    {
        try {
            $quiz = $this->quizRepository->getQuizById($quizId);
            $imgFile = $this->saveFile($request->image);
            $questionDTO = QuestionDTO::fromRequest($request, $quiz->id, $imgFile);

            $data = $this->createQuestion($questionDTO);

            // Asegúrate de verificar que el resultado es exitoso
            if (!$data['success']) {
                return [
                    'success' => false,
                    'message' => $data['message'] ?? 'Error creating question'
                ];
            }

            return [
                'success' => true,
                'data' => $data['data']
            ];
        } catch (Exception $exception) {
            return [
                'success' => false,
                'message' => $exception->getMessage() // Error al obtener o crear
            ];
        }
    }

    private function createQuestion(QuestionDTO $questionDTO)
    {
        try {
            $questionForQuiz = $this->questionRepository->createQuestion($questionDTO);
            return [
                'success' => true,
                'data' => $questionForQuiz
            ];
        } catch (\Exception $exception) {
            return [
                'success' => false,
                'message' => "Error creating question: " . $exception->getMessage()
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

    public function updateImage(UploadedFile $file, int $questionId): array
    {

        try {
            $question = $this->findQuestionOrFail($questionId);
            if ($question->image) {
                $this->deleteFile($question->image);
            }
            $path = $file->store('questions', 'public');
            $imageUrl = Storage::url($path);
            $updateImageQuestion = $this->questionRepository->updateImage($question, $imageUrl);
            return ['success' => true, "data" => $updateImageQuestion, 'message' => 'Record updated successfully'];
        } catch (\Exception $exception) {
            return [
                'success' => false,
                'message' => $exception->getMessage()
            ];
        }
    }

    private function deleteFile(string $filePath): void
    {
        $relativePath = str_replace('/storage/', '', $filePath);
        if (Storage::disk('public')->exists($relativePath)) {
            Storage::disk('public')->delete($relativePath);
        }
    }

    public function deleteQuestion($id)
    {
        // TODO: ejecutar job para poder actualizar los puntos de los usuarios
        try {
            $this->authServices->validateRole();
            $question = $this->findQuestionOrFail($id);
            if ($question->image) {
                $this->deleteFile($question->image);
            }
            $this->questionRepository->deletedQuestion($question->id);
            return ['success' => true, 'data' => $question];
        } catch (Exception $exception) {
            return [
                'success' => false,
                'message' => $exception->getMessage()
            ];
        }
    }
}
