<?php

namespace App\Providers;

use App\Models\Event;
use Illuminate\Support\Facades\Route;
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
    public function boot(): void
    {
        // Explicit route model binding for Event
        Route::bind('event', function ($value) {
            return Event::where('id', $value)
                ->firstOrFail();
        });
    }
}
