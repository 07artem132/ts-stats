<?php

namespace Api\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Debugbar;

/**
 * Class LogSuccessfulLogin
 * @package Api\Listeners
 */
class LogSuccessfulLogin
{
    /**
     * LogSuccessfulLogin constructor.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  Login  $event
     * @return void
     */
    public function handle(Login $event)
    {
        Debugbar::addMessage($event);
    }
}
