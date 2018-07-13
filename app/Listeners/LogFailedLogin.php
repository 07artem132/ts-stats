<?php

namespace Api\Listeners;

use Illuminate\Auth\Events\Failed;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Debugbar;

/**
 * Class LogFailedLogin
 * @package Api\Listeners
 */
class LogFailedLogin
{
    /**
     * LogFailedLogin constructor.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  Failed  $event
     * @return void
     */
    public function handle(Failed $event)
    {
    }
}
