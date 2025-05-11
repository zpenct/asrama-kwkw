<?php

namespace App\Providers;

use App\Listeners\SendResetPasswordEmail;
use Filament\Http\Responses\Auth\Contracts\LoginResponse;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(
            LoginResponse::class,
            \App\Http\Responses\LoginResponse::class
        );
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
