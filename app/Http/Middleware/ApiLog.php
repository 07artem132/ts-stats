<?php
/**
 * Created by PhpStorm.
 * User: Artem
 * Date: 07.07.2017
 * Time: 19:32
 */

namespace App\Http\Middleware;

use Closure;
use App\ApiLog as DbLog;
use \Illuminate\Http\Request;

class ApiLog {
	public function handle(Request $request, Closure $next ) {
		$request->StartTime = microtime( true );

		return $next( $request );
	}

	public function terminate(Request $request, $response ) {
		if ( ! empty( $request->header( 'X-token' ) ) ) {
			$log               = new DbLog;
			$log->token        = $request->header( 'X-token' );
			$log->method       = $request->path();
			$log->execute_time = microtime( true ) - $request->StartTime;
			$log->client_ip    = $request->ip();
			$log->save();
		}
	}

}
