<?php

namespace App\Http\Controllers;

use App\Instance;
use App\ClientContry;
use Illuminate\Http\Request;
use App\Charts\StatisticsInstanceCharts;
use App\Charts\StatisticUsageClientVersionMonthCharts;
use App\Charts\StatisticClientByCountryMonthCharts;
use App\Charts\StatisticPopularServerConfigSlotsMonthCharts;
use App\Charts\StatisticTopClientPlatformMonthCharts;
use Illuminate\Support\Facades\Redis;

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
		$realTimeCharts           = new StatisticsInstanceCharts;
		$usageClientVersion       = new StatisticUsageClientVersionMonthCharts;
		$ClientByContry           = new StatisticClientByCountryMonthCharts;
		$PopularServerConfigSlots = new StatisticPopularServerConfigSlotsMonthCharts;
		$TopPlatform              = new StatisticTopClientPlatformMonthCharts;

		return view( 'home', [
			'realTimeCharts'           => $realTimeCharts,
			'usageClientVersion'       => $usageClientVersion,
			'ClientByContry'           => $ClientByContry,
			'PopularServerConfigSlots' => $PopularServerConfigSlots,
			'TopPlatform'              => $TopPlatform,
		] );
	}
}
