<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuestionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'question' => $this->question,
            'image' => $this->image,
            'correct_answer' => $this->correct_answer,
            'answer_count' => $this->answer_count,
            'quiz_name' => $this->quiz->title,
            'quiz_id' => $this->quiz->id
        ];
    }
}
