<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Instance;
use  \Illuminate\View\View;
use \Illuminate\Http\RedirectResponse;

class InstanseController extends Controller {
	/**
	 * InstanseController constructor.
	 */
	public function __construct() {
		$this->middleware( 'auth' );
	}

	/**
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function list(Request $request): View {
		$instances = Instance::orderBy( 'id' )->paginate( $request->input('limit',10) );


		return view( 'InstanseList', [ 'instances' => $instances ] );
	}

	/**
	 * @param Request $request
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 * @throws \Exception
	 */
	function delete( Request $request ): RedirectResponse {
		Instance::find( $request->id )->delete();

		return redirect()->back();
	}

	/**
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function add(): View {
		return view( 'addInstanse' );
	}

	/**
	 * @param Request $request
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	function edit( Request $request ): View {
		$config = Instance::find( $request->id );

		return view( 'EditInstanse', [ 'config' => $config ] );

	}

	/**
	 * @param Request $request
	 *
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 * @throws \Throwable
	 */
	function update( Request $request ): RedirectResponse {
		$TeamspeakInstances            = Instance::find( $request->id );
		$TeamspeakInstances->ipaddress = $request->input( 'ip' );
		$TeamspeakInstances->username  = $request->input( 'Login' );
		$TeamspeakInstances->password  = $request->input( 'passwd' );
		$TeamspeakInstances->port      = $request->input( 'port' );
		if ( $request->input( 'is_enable' ) === 'on' ) {
			$TeamspeakInstances->is_enabled = 1;
		} else {
			$TeamspeakInstances->is_enabled = 0;
		}
		$TeamspeakInstances->saveOrFail();

		return redirect( '/list' );

	}

	/**
	 * @param Request $request
	 *
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 * @throws \Throwable
	 */
	public function store( Request $request ): RedirectResponse {

		$TeamspeakInstances            = new Instance();
		$TeamspeakInstances->ipaddress = $request->input( 'ip' );
		$TeamspeakInstances->username  = $request->input( 'Login' );
		$TeamspeakInstances->password  = $request->input( 'passwd' );
		$TeamspeakInstances->port      = $request->input( 'port' );
		if ( $request->input( 'is_enable' ) === 'on' ) {
			$TeamspeakInstances->is_enabled = 1;
		} else {
			$TeamspeakInstances->is_enabled = 0;
		}
		$TeamspeakInstances->saveOrFail();

		return redirect( '/list' );
	}

}
