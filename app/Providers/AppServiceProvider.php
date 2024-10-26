<?php

namespace App\Providers;

use App\Policies\AdminPolicy;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Passport::loadKeysFrom(__DIR__ . '/../secrets/oauth');
        Gate::define('validate-role', [AdminPolicy::class, 'ValidateAdmin']);
    }
}
