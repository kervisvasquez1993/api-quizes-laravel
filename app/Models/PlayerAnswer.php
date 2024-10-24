<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlayerAnswer extends Model
{
    protected $fillable = ['user_id', 'question_id', 'given_answer', 'is_correct'];

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
