<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\{
    Photo,
};
use App\Observers\PhotoObserver;

class ObserverServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
        Photo::observe(PhotoObserver::class);
    }
}
