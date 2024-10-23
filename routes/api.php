<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/register', [AuthController::class, 'register'])->name('register');

Route::post('/test', [AuthController::class, 'test'])->name('test');

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');
