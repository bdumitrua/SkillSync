<?php

namespace App\Observers;

use App\Models\Team;
use App\Events\TeamDeleteEvent;

class TeamObserver
{
    /**
     * Handle the Team "updated" event.
     */
    public function updated(Team $team): void
    {
        //
    }

    /**
     * Handle the Team "deleted" event.
     */
    public function deleted(Team $team): void
    {
        event(new TeamDeleteEvent($team));
    }
}
