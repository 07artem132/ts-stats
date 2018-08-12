<?php

/**
 * Created by PhpStorm.
 * User: Artem
 * Date: 27.06.2017
 * Time: 17:42
 */

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\StatisticInstance;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use App\Traits\RestSuccessResponseTrait;
use App\Instance;
use App\Traits\InstanceListTrait;
use App\Charts\StatisticTopClientPlatformMonthCharts;
use App\Charts\StatisticClientByCountryMonthCharts;
use App\Charts\StatisticUsageClientVersionMonthCharts;
use App\Charts\StatisticPopularServerConfigSlotsMonthCharts;
use App\Charts\StatisticsInstanceCharts;
use Illuminate\Http\Request;

/**
 * Class Statistics
 * @package Api\Http\Controllers\TeamSpeak
 */
class InstanceStatisticsController extends Controller {
	use RestSuccessResponseTrait;
	use InstanceListTrait;

	/**
	 * @var int Уникальный идентификатор сервера
	 */
	private $server_id;

	/**
	 * @param int $server_id Уникальный идентификатор сервера
	 *
	 * @return JsonResponse Обьект с данными для ответа
	 */
	function Year( int $server_id ): JsonResponse {
		$this->server_id = $server_id;

		$data = Cache::remember( 'InstanseStatisticsYearServerID-' . $server_id, Carbon::now()->addMinutes( config( 'ApiCache.TeamSpeak.Statistics.Instanse.Year' ) ), function () {
			return StatisticInstance::InstanceId( $this->server_id )->StatYear()->DayAvage()->get();
		} );

		return $this->jsonResponse( $data );
	}

	/**
	 * @param int $server_id Уникальный идентификатор сервера
	 *
	 * @return JsonResponse Обьект с данными для ответа
	 */
	function Month( int $server_id ): JsonResponse {
		$this->server_id = $server_id;

		$data = Cache::remember( 'InstanseStatisticsMonthServerID-' . $server_id, Carbon::now()->addMinutes( config( 'ApiCache.TeamSpeak.Statistics.Instanse.Month' ) ), function () {
			return StatisticInstance::InstanceId( $this->server_id )->StatMonth()->HourAvage()->get();
		} );

		return $this->jsonResponse( $data );
	}

	/**
	 * @param int $server_id Уникальный идентификатор сервера
	 *
	 * @return JsonResponse Обьект с данными для ответа
	 */
	function Week( int $server_id ): JsonResponse {
		$this->server_id = $server_id;

		$data = Cache::remember( 'InstanseStatisticsWeekServerID-' . $server_id, Carbon::now()->addMinutes( config( 'ApiCache.TeamSpeak.Statistics.Instanse.Week' ) ), function () {
			return StatisticInstance::InstanceId( $this->server_id )->StatWeek()->HalfHourAvage()->get();
		} );

		return $this->jsonResponse( $data );
	}

	/**
	 * @param int $server_id Уникальный идентификатор сервера
	 *
	 * @return JsonResponse Обьект с данными для ответа
	 */
	function Day( int $server_id ): JsonResponse {
		$this->server_id = $server_id;

		$data = Cache::remember( 'InstanseStatisticsDayServerID-' . $server_id, Carbon::now()->addMinutes( config( 'ApiCache.TeamSpeak.Statistics.Instanse.Day' ) ), function () {
			return StatisticInstance::InstanceId( $this->server_id )->StatDay()->FiveMinutesAvage()->get();
		} );

		return $this->jsonResponse( $data );
	}

	/**
	 * @param int $server_id Уникальный идентификатор сервера
	 *
	 * @return JsonResponse Обьект с данными для ответа
	 */
	function Realtime( int $server_id ): JsonResponse {
		$this->server_id = $server_id;

		$data = Cache::remember( 'InstanseStatisticsRealtimeServerID-' . $server_id, Carbon::now()->addMinutes( config( 'ApiCache.TeamSpeak.Statistics.Instanse.Realtime' ) ), function () {
			return StatisticInstance::InstanceId( $this->server_id )->StatRealtime()->get();
		} );

		return $this->jsonResponse( $data );
	}

	function list( Request $request ) {
		$TopPlatformClientInstanceCharts        = new StatisticTopClientPlatformMonthCharts();
		$ClientByCountryInstanceCharts          = new StatisticClientByCountryMonthCharts();
		$usageClientVersionInstanceCharts       = new StatisticUsageClientVersionMonthCharts();
		$PopularServerConfigSlotsInstanceCharts = new StatisticPopularServerConfigSlotsMonthCharts();
		$InstanceStatisticsCharts               = new StatisticsInstanceCharts( $request->input( 'instance-id', null ), $request->input( 'stats-type-id', 1 ) );

		return view( 'InstanseStatistics', [
			'InstanceStatisticsCharts'               => $InstanceStatisticsCharts,
			'usageClientVersionInstanceCharts'       => $usageClientVersionInstanceCharts,
			'ClientByCountryInstanceCharts'          => $ClientByCountryInstanceCharts,
			'PopularServerConfigSlotsInstanceCharts' => $PopularServerConfigSlotsInstanceCharts,
			'TopPlatformClientInstanceCharts'        => $TopPlatformClientInstanceCharts,
			'instances'                              => $this->GetInstanceList()
		] );
	}
}