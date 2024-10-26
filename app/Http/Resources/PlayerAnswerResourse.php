<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PlayerAnswerResourse extends JsonResource
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
            'user_id' => $this->user_id,
            'question_id' => $this->question_id,
            'given_answer' => $this->given_answer,
            'is_correct' => $this->is_correct,
            // 'question' => $this->question , 
        ];
    }
}
