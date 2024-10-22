<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $table = 'questions';
    protected $fillable = [
        'quiz_id',
        'question_text',
        'image'
    ];
    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }
    public function correctAnswer()
    {
        return $this->hasOne(QuizQuestionCorrect::class);
    }
}
