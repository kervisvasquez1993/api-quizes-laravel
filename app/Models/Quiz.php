<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    protected $fillable = [
        'title',
        'description',
        'created_by'
    ];
    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function correctAnswers()
    {
        return $this->hasMany(QuizQuestionCorrect::class);
    }
}
