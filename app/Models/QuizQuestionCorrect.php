<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizQuestionCorrect extends Model
{
    use HasFactory;

    protected $table = 'quiz_questions_correct';

    // Campos fillable para asignación masiva
    protected $fillable = [
        'quiz_id',
        'question_id',
        'message_correct',
        'message_incorrect'
    ];
    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }
    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}
