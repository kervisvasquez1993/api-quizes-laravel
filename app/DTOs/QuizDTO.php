<?php

namespace App\DTOs;

use App\Http\Requests\Quiz\StoreQuizRequest;

class QuizDTO
{
    public function __construct(
        private readonly string $title,
        private readonly string $description,
        private readonly int $userId,
    ) {}

    public static function fromRequest(StoreQuizRequest $request, int $userId): self
    {
        return new self(
            title: $request->validated('title'),
            description: $request->validated('description'),
            userId: $userId
        );
    }

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
            'user_id' => $this->userId
        ];
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): string
    {
        return $this->title;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }
}
