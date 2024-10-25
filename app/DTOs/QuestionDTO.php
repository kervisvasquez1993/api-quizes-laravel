<?php

namespace App\DTOs;

use App\Http\Requests\Question\StoreQuestionRequest;
use App\Http\Requests\Question\UpdateQuestionRequest;
use Illuminate\Support\Facades\Auth;

class QuestionDTO
{
    public function __construct(
        private readonly string $quizId,
        private readonly string $question,
        private readonly ?string $image,
        private readonly bool $correctAnswer,
        private readonly int $userId,
    ) {}

    public static function fromRequest(StoreQuestionRequest $request, int $quizId, $img = null): self
    {
        return new self(
            quizId: $quizId,
            question: $request->validated('question'),
            image: $img,
            correctAnswer: $request->validated('correct_answer'),
            userId: Auth::user()->id
        );
    }

    public static function fromUpdateRequest(UpdateQuestionRequest $request): self
    {
        return new self(
            quizId: $request->validated('quiz_id'),
            question: $request->validated('question'),
            image: null,
            correctAnswer: $request->validated('correct_answer'),
            userId: Auth::user()->id
        );
    }

    public function toArray(): array
    {
        return [
            'quiz_id' => $this->quizId,
            'question' => $this->question,
            'image' => $this->image,
            'correct_answer' => $this->correctAnswer,
            'user_id' => $this->userId,
        ];
    }

    public function getQuizId(): int
    {
        return $this->quizId;
    }

    public function getText(): string
    {
        return $this->question;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function getCorrectAnswer(): bool
    {
        return $this->correctAnswer;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }
}
