<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    protected $fillable = [
        'question_title',
        'img',
        'created_by',
        'question_answer'
    ];

}
