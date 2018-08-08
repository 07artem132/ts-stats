<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Charts\StatisticsInstancesRealTimeCharts;
use App\Charts\StatisticUsageClientVersionMonthCharts;
use App\Charts\StatisticClientByCountryMonthCharts;


use  \Illuminate\View\View;

class HomeController extends Controller {
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct() {
		$this->middleware( 'auth' );
	}

	/**
	 * Show the application dashboard.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(): View {
		$realTimeCharts     = new StatisticsInstancesRealTimeCharts;
		$usageClientVersion = new StatisticUsageClientVersionMonthCharts;
		$ClientByContry     = new StatisticClientByCountryMonthCharts;

		return view( 'home', [
			'realTimeCharts'     => $realTimeCharts,
			'usageClientVersion' => $usageClientVersion,
			'ClientByContry'     => $ClientByContry,
		] );
	}
}
