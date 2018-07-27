<?php

namespace App\Http\Controllers;

use App\TaskLog;
use App\Task;
use Illuminate\Http\Request;
use  \Illuminate\View\View;
use \Illuminate\Http\RedirectResponse;

class CronController extends Controller {
	public function __construct() {
		$this->middleware( 'auth' );
	}

	/**
	 * @param Request $request
	 *
	 * @return \Illuminate\Contracts\View\Factory|View
	 */
	function log( Request $request ): View {
		$logs = TaskLog::where( 'task_id', '=', $request->id )->orderBy( 'id', 'desc' )->paginate( 30 );

		return view( 'cronLog', [ 'logs' => $logs ] );
	}

	function list(): View {
		$tasks = Task::all();

		return view( 'cronList', [ 'tasks' => $tasks ] );
	}

	function deactivate( Request $request ): RedirectResponse {
		$Task             = Task::find( $request->id );
		$Task->is_enabled = 0;
		$Task->save();

		return redirect()->back();
	}

	function activate( Request $request ): RedirectResponse {
		$Task             = Task::find( $request->id );
		$Task->is_enabled = 1;
		$Task->save();

		return redirect()->back();
	}

	function edit( Request $request ): View {
		$frequency = Task::find( $request->id );

		return view( 'cronEdit', [ 'frequency' => $frequency->frequency ] );

	}

	//todo validate rule https://tomlankhorst.nl/laravel-5-cron-expression-validation/
	function update( Request $request ): RedirectResponse {
		$Task            = Task::find( $request->id );
		$Task->frequency = $request->frequency;
		$Task->save();

		return redirect( '/cron' );
	}
}
