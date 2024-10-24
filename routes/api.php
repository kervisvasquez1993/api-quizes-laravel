<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\QuestionController;
use App\Http\Controllers\Api\QuizController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/register', [AuthController::class, 'register'])->name('register');


Route::get('/quiz', [QuizController::class, 'index'])->name('listarQuiz');
Route::get('/quiz/{id}', [QuizController::class, 'show'])->name('showQuiz');




Route::middleware('auth:api')->group(function () {
    Route::post('/quiz', [QuizController::class, 'store'])->name('createQuiz');
    Route::put('/quiz/{id}', [QuizController::class, 'update'])->name('updateQuiz');
    Route::delete('/quiz/{id}', [QuizController::class, 'destroy'])->name('updateQuiz');


    // questions
    Route::post('/quiz/{quizId}/questions', [QuestionController::class, 'store'])->name('createQuestionForQuiz');
    Route::put('/questions/{id}', [QuestionController::class, 'update'])->name('updateQuestion');
});
