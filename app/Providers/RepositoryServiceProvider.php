<?php

namespace App\Providers;

use App\Interface\Auth\AuthRepositoryInterface;
use App\Interface\PlayerAnwer\PlayerAnswerRepositoryInterface;
use App\Interface\Question\QuestionRepositoryInterface;
use App\Interface\Quiz\QuizRepositoryInterface;
use App\Repositories\Auth\AuthRepository;
use App\Repositories\PlayerAnswer\PlayerAnswerRepository;
use App\Repositories\Question\QuestionRepository;
use App\Repositories\Quiz\QuizRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(AuthRepositoryInterface::class, AuthRepository::class);
        $this->app->bind(QuizRepositoryInterface::class, QuizRepository::class);
        $this->app->bind(QuestionRepositoryInterface::class, QuestionRepository::class);
        $this->app->bind(PlayerAnswerRepositoryInterface::class, PlayerAnswerRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
