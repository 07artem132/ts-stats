<?php

namespace Api\Listeners;

use Illuminate\Auth\Events\Logout;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Debugbar;

/**
 * Class LogSuccessfulLogout
 * @package Api\Listeners
 */
class LogSuccessfulLogout
{
    /**
     * LogSuccessfulLogout constructor.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  Logout  $event
     * @return void
     */
    public function handle(Logout $event)
    {
        Debugbar::addMessage($event);
    }
}
