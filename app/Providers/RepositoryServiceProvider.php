<?php

namespace App\Providers;

use App\Interface\Auth\AuthRepositoryInterface;
use App\Interface\Quiz\QuizRepositoryInterface;
use App\Repositories\Auth\AuthRepository;
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
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
