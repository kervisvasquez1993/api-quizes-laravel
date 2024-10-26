<?php

namespace App\DTOs;

use App\Http\Requests\PlayerAnswer\StorePlayerAnswerRequest;
use Illuminate\Support\Facades\Auth;
class PlayerAnswerDTO
{
    public function __construct(
        private readonly int $questionId,
        private readonly int $userId,
        private readonly bool $givenAnswer,
        private readonly bool $isCorrect
    ) {}

    public static function fromRequest(StorePlayerAnswerRequest $request, int $questionId, bool $isCorrect): self
    {
        return new self(
            questionId: $questionId,
            userId: Auth::user()->id,
            givenAnswer: (bool) $request->validated('given_answer'),
            isCorrect: $isCorrect
        );
    }

    public function toArray(): array
    {
        return [
            'question_id' => $this->questionId,
            'user_id' => $this->userId,
            'given_answer' => $this->givenAnswer,
            'is_correct' => $this->isCorrect
        ];
    }

    public function getQuestionId(): int
    {
        return $this->questionId;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getGivenAnswer(): bool
    {
        return $this->givenAnswer;
    }

    public function getIsCorrect(): bool
    {
        return $this->isCorrect;
    }
}
