<?php

namespace App\Observers;

use App\Models\Download;
use Cache;

class DownloadObserver
{
    /**
     * Handle the Download "created" event.
     */
    public function created(Download $download): void
    {
        //
        Cache::flush();
    }

    /**
     * Handle the Download "updated" event.
     */
    public function updated(Download $download): void
    {
        //
        Cache::flush();
    }

    /**
     * Handle the Download "deleted" event.
     */
    public function deleted(Download $download): void
    {
        //
        Cache::flush();
    }

    /**
     * Handle the Download "restored" event.
     */
    public function restored(Download $download): void
    {
        //
        Cache::flush();
    }

    /**
     * Handle the Download "force deleted" event.
     */
    public function forceDeleted(Download $download): void
    {
        //
        Cache::flush();
    }
}
