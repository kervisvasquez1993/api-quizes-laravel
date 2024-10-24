<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\QuizController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/register', [AuthController::class, 'register'])->name('register');

Route::middleware('auth:api')->group(function () {
    Route::post('/quiz', [QuizController::class, 'store'])->name('register');
    Route::put('/quiz/{id}', [QuizController::class, 'update'])->name('updateQuiz');
});