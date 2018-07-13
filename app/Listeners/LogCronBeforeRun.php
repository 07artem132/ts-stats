<?php

namespace Api\Listeners;

use Api\Events\CronBeforeRun;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class LogCronBeforeRun
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
     * @param  CronBeforeRun  $event
     * @return void
     */
    public function handle(CronBeforeRun $event)
    {
        //
    }
}
