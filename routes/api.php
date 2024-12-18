<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PlayerAnswerController;
use App\Http\Controllers\Api\QuestionController;
use App\Http\Controllers\Api\QuizController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/register', [AuthController::class, 'register'])->name('register');

// quiz
Route::get('/quiz', [QuizController::class, 'index'])->name('listarQuiz');
Route::get('/quiz/{id}', [QuizController::class, 'show'])->name('showQuiz');
// questions
Route::get('/quiz/{quizId}/questions', [QuestionController::class, 'questionForQuiz'])->name('getQuestionForQuiz');
Route::get('/questions', [QuestionController::class, 'index'])->name('listAll');
Route::get('/questions/{id}', [QuestionController::class, 'show'])->name('showQuestion');
// player answer 
Route::get('/user/{id}/answers', [PlayerAnswerController::class, 'getUserAnswers'])->name('listUserAnswers');
Route::get('questions/{id}/answers', [PlayerAnswerController::class, 'getAnswersByQuestion'])->name('listQuestionAnswers');
// user
Route::get('/list-user-point', [AuthController::class, 'listUserPoint'])->name('listPointByUser');
Route::get('/players-position', [PlayerAnswerController::class, 'pointForUser'])->name('listPointByUser');



Route::middleware('auth:api')->group(function () {
    //user
    Route::get('/me', [AuthController::class, 'me'])->name('me');
    Route::get('/profile', [AuthController::class, 'profile'])->name('profile');

    // quiz
    Route::post('/quiz', [QuizController::class, 'store'])->name('createQuiz');
    Route::put('/quiz/{id}', [QuizController::class, 'update'])->name('updateQuiz');
    Route::delete('/quiz/{id}', [QuizController::class, 'destroy'])->name('updateQuiz');
    // questions
    Route::post('/quiz/{quizId}/questions', [QuestionController::class, 'store'])->name('createQuestionForQuiz');
    Route::put('/questions/{id}', [QuestionController::class, 'update'])->name('updateQuestion');
    Route::post('/questions/{id}/image', [QuestionController::class, 'updateImage'])->name('updateImageQuestion');
    Route::post('/questions/{questionsId}/player-answer', [PlayerAnswerController::class, 'store'])->name('playerAnswerQuestion');
    Route::delete('/questions/{id}', [QuestionController::class, 'destroy'])->name('deletedQuestion');
    // player answer
    Route::get('/my-answer-question', [PlayerAnswerController::class, 'myAnswersQuestion'])->name('listMyAnswers');
});
