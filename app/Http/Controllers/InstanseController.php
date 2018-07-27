<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TeamspeakInstances;
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
	public function list(): View {
		return view( 'InstanseList', [ 'Instanses' => TeamspeakInstances::all() ] );
	}

	/**
	 * @param Request $request
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	function activate( Request $request ): RedirectResponse {
		$TeamspeakInstances             = TeamspeakInstances::find( $request->id );
		$TeamspeakInstances->is_enabled = 1;
		$TeamspeakInstances->save();

		return redirect()->back();
	}

	/**
	 * @param Request $request
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	function deactivate( Request $request ): RedirectResponse {
		$TeamspeakInstances             = TeamspeakInstances::find( $request->id );
		$TeamspeakInstances->is_enabled = 0;
		$TeamspeakInstances->save();

		return redirect()->back();
	}

	/**
	 * @param Request $request
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 * @throws \Exception
	 */
	function delete( Request $request ): RedirectResponse {
		TeamspeakInstances::find( $request->id )->delete();

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
		$config = TeamspeakInstances::find( $request->id );

		return view( 'EditInstanse', [ 'config' => $config ] );

	}

	/**
	 * @param Request $request
	 *
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 * @throws \Throwable
	 */
	function update( Request $request ): RedirectResponse {
		$TeamspeakInstances            = TeamspeakInstances::find( $request->id );
		$TeamspeakInstances->name      = $request->input( 'name' );
		$TeamspeakInstances->ipaddress = $request->input( 'ip' );
		$TeamspeakInstances->hostname  = $request->input( 'hostname' );
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

		$TeamspeakInstances            = new TeamspeakInstances();
		$TeamspeakInstances->name      = $request->input( 'name' );
		$TeamspeakInstances->ipaddress = $request->input( 'ip' );
		$TeamspeakInstances->hostname  = $request->input( 'hostname' );
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
