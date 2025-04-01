<?php

namespace App\Providers;

use Illuminate\Auth\Events\Login;
use Illuminate\Support\ServiceProvider;
use App\Listeners\SendResetPasswordEmail;

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
        //
    }

    protected $listen = [
        Login::class => [
            SendResetPasswordEmail::class,
        ],
    ];
}