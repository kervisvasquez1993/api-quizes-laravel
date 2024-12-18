<?php

namespace App\Services\Question;

use App\DTOs\QuestionDTO;
use App\Http\Requests\Question\StoreQuestionRequest;
use App\Interface\Quiz\QuizRepositoryInterface;
use App\Jobs\CalculateUserPoints;
use App\Jobs\UpdatePlayerAnswersJob;
use App\Services\Auth\AuthServices;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Interface\Question\QuestionRepositoryInterface;
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
        return  $this->questionRepository->findQuestionById($id);
    }
    public function show($id)
    {
        try {
            $data =  $this->questionRepository->findQuestionById($id);
            return [
                'success' => true,
                'data' => $data
            ];
        } catch (Exception $exception) {
            return [
                'success' => false,
                'message' => $exception->getMessage()
            ];
        }
    }


    public function createQuestionWithQuiz(StoreQuestionRequest $request, $quizId)
    {
        try {
            $quiz = $this->quizRepository->getQuizById($quizId);
            $imgFile = $this->saveFile($request->image);
            $questionDTO = QuestionDTO::fromRequest($request, $quiz->id, $imgFile);
            $data = $this->createQuestion($questionDTO);
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
                'message' => $exception->getMessage()
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
            $question = $this->questionRepository->findQuestionById($id);

            $originalAnswer = $question->correct_answer;
            $updateQuestion = $this->questionRepository->updateQuestion($question, $questionDTO);
            if ($updateQuestion->correct_answer !== $originalAnswer) {
                UpdatePlayerAnswersJob::dispatch($updateQuestion);
            }
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
            $question = $this->questionRepository->findQuestionById($questionId);
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

        try {
            $this->authServices->validateRole();
            $question = $this->questionRepository->findQuestionById($id);
            if ($question->image) {
                $this->deleteFile($question->image);
            }
            $this->questionRepository->deletedQuestion($question->id);
            CalculateUserPoints::dispatch();
            return ['success' => true, 'data' => $question];
        } catch (Exception $exception) {
            return [
                'success' => false,
                'message' => $exception->getMessage()
            ];
        }
    }
}
