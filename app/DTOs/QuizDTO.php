<?php

namespace App\DTOs;

use App\Http\Requests\Quiz\StoreQuizRequest;
use App\Http\Requests\Quiz\UpdateQuizRequest;
use Illuminate\Support\Facades\Auth;

class QuizDTO
{
    public function __construct(
        private readonly string $title,
        private readonly string $description,
        private readonly int $userId,
    ) {}

    public static function fromRequest(StoreQuizRequest $request): self
    {
        return new self(
            title: $request->validated('title'),
            description: $request->validated('description'),
            userId: Auth::user()->id
        );
    }
    public static function fromUpdateRequest(UpdateQuizRequest $request): self
    {
        return new self(
            title: $request->validated('title'),
            description: $request->validated('description'),
            userId: Auth::user()->id
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
        return $this->description;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }
}
