<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Notifications\ResetPassword;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('App\Interfaces\UserInterface', 'App\Repositories\UserRepository');
        $this->app->bind('App\Interfaces\ProductInterface', 'App\Repositories\ProductRepository');
        //$this->app->bind('App\Services\ProductService', 'App\Services\ProductService');
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        ResetPassword::createUrlUsing(function ($user, string $token) {
            return env('SPA_URL') . '/reset-password?token=' . $token;
        });
    }
}
