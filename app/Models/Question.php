<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $table = 'questions';
    protected $fillable = ['quiz_id', 'question', 'image', 'correct_answer', 'user_id'];
}
