<?php

namespace App\Providers;

use Log;
use Event;
use Illuminate\Support\ServiceProvider;

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
    public function boot()
    {
        Event::listen('kernel.handled', function ($request, $response) {
            if ($request->isMethod('POST')) {
                Log::debug('CSRF Token:', ['token' => $request->input('_token')]);
            }
        });
    }
}