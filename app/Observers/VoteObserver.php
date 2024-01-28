<?php

namespace App\Observers;

use App\Models\Vote;

use Cache;

class VoteObserver
{
    /**
     * Handle the Vote "created" event.
     */
    public function created(Vote $vote): void
    {
        //
        Cache::flush();
    }

    /**
     * Handle the Vote "updated" event.
     */
    public function updated(Vote $vote): void
    {
        //
        Cache::flush();
    }

    /**
     * Handle the Vote "deleted" event.
     */
    public function deleted(Vote $vote): void
    {
        //
        Cache::flush();
    }

    /**
     * Handle the Vote "restored" event.
     */
    public function restored(Vote $vote): void
    {
        //
        Cache::flush();
    }

    /**
     * Handle the Vote "force deleted" event.
     */
    public function forceDeleted(Vote $vote): void
    {
        //
        Cache::flush();
    }
}
