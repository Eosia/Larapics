<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\{
    Photo,
    Vote,
    Download,
};
use App\Observers\{
    PhotoObserver,
    VoteObserver,
    DownloadObserver,
};

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
        Vote::observe(VoteObserver::class);
        Download::observe(DownloadObserver::class);
    }
}
