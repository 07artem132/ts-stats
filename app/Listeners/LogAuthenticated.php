<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Authenticated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Debugbar;

/**
 * Class LogAuthenticated
 * @package App\Listeners
 */
class LogAuthenticated {
	/**
	 * LogAuthenticated constructor.
	 */
	public function __construct() {
		//
	}

	/**
	 * Handle the event.
	 *
	 * @param  Authenticated $event
	 *
	 * @return void
	 */
	public function handle( Authenticated $event ) {
	}
}
