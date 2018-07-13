<?php

namespace Api\Listeners;

use App\Events\CronLocked;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class LogCronLockedFile
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  CronLocked  $event
     * @return void
     */
    public function handle(CronLocked $event)
    {
        //
    }
}
