<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserResponse extends Model
{
    use HasFactory;

    protected $table = 'user_responses';
    protected $fillable = [
        'user_id',
        'quiz_id',
        'question_id',
        'is_correct'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }


    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}
