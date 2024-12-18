<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    protected $table = 'quizzes';
    protected $fillable = [
        'title',
        'description',
        'user_id',
    ];

    public function questions()
    {
        return $this->hasMany(Question::class, 'quiz_id');
    }
}
