<?php

namespace Api\Listeners;

use Api\Events\CronAfterRun;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class LogCronAfterRun
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
     * @param  CronAfterRun  $event
     * @return void
     */
    public function handle(CronAfterRun $event)
    {
        //
    }
}
