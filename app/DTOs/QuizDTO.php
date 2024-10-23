<?php

namespace App\DTOs;

use App\Http\Requests\Quiz\StoreQuizRequest;

class QuizDTO
{
    public function __construct(
        private readonly string $questionTitle,
        private readonly string $questionAnswer,
        private readonly int $userId,
        private readonly ?string $img = null
    ) {}

    public static function fromRequest(StoreQuizRequest $request, int $userId, ?string $img = null): self
    {
        return new self(
            questionTitle: $request->validated('question_title'),
            questionAnswer: (bool) $request->validated('question_answer'),
            userId: $userId,
            img: $img 
        );
    }

    public function toArray(): array
    {
        return [
            'question_title' => $this->questionTitle,
            'question_answer' => $this->questionAnswer,
            'user_id' => $this->userId,
            'img' => $this->img
        ];
    }

    public function getQuestionTitle(): string
    {
        return $this->questionTitle;
    }

    public function getQuestionAnswer(): bool
    {
        return $this->questionAnswer;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getImg(): ?string
    {
        return $this->img;
    }
}
