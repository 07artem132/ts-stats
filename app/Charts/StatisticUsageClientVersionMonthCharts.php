<?php

namespace App\Charts;

use App\ClientVersion;
use Illuminate\Support\Collection;
use ConsoleTVs\Charts\Classes\Chartjs\Chart;
use Illuminate\Support\Facades\Redis;

class StatisticUsageClientVersionMonthCharts extends Chart {
	private $StatisticsClientVersions;

	/**
	 * Initializes the chart.
	 *
	 * @return void
	 */
	public function __construct() {
		parent::__construct();
		$this->redis                    = Redis::connection();
		$this->StatisticsClientVersions = $this->getStatistics();
		$this->setDataUseVersions();
		$this->setLabels();
		$this->setOptions();
	}

	private function getStatistics(): Collection {
		$statistics = collect();

		$result = $this->redis->command( 'HGETALL', [
			config( 'cache.prefix' ) . ':clientVersion'
		] );

		if ( empty( $result ) ) {
			$result = ClientVersion::all()->sortBy( 'id' )->pluck( 'id' );
		} else {
			$result = array_flip( $result );
			ksort( $result );
			$result = array_flip( $result );
		}

		foreach ( $result as $item ) {
			$response = $this->redis->command( 'hget', [
				config( 'cache.prefix' ) . ':StatisticsClientVersion',
				$item
			] );
			if ( $response === false ) {
				$response = (int) ClientVersion::findOrFail( $item )->clientUseVersionMonth();

				$this->redis->command( 'hset', [
					config( 'cache.prefix' ) . ':StatisticsClientVersion',
					$item,
					$response
				] );
			}

			$statistics[] = collect( [
				'version_id' => $item,
				'use'        => (int) $response
			] );
		}

		$statistics   = $statistics->sortByDesc( 'use' );
		$other        = $statistics->splice( 6 );
		$statistics[] = collect( [
			'version' => 'Остальные',
			'use'     => (int) $other->sum( 'use' )
		] );

		$ClientVersion = ClientVersion::whereIn( 'id', $statistics->pluck( 'version_id' ) )->get()->getDictionary();

		for ( $i = 0; $i < $statistics->count(); $i ++ ) {
			if ( ! $statistics[ $i ]->has( 'version' ) ) {
				$statistics[ $i ]['version'] = $ClientVersion[ $statistics[ $i ]['version_id'] ]->major . '.' . $ClientVersion[ $statistics[ $i ]['version_id'] ]->minor . '.' . $ClientVersion[ $statistics[ $i ]['version_id'] ]->patch;
				unset( $statistics[ $i ]['version_id'] );
			}
		}
		unset( $ClientVersion );

		return $statistics;
	}

	private function setOptions(): void {
		$this->options( [
			'type'                => 'pie',
			'responsive'          => true,
			'tooltips'            => [
				'enabled' => true
			],
			'title'               => [
				'display' => true,
				'text'    => 'Статистика за последний месяц по используемым версиям клиента'
			],
			'legend'              => [
				'display' => true
			],
			'maintainAspectRatio' => false,
			'scales'              => [
				'xAxes' => [
					'ticks' => [
						'beginAtZero' => false,
						'display'     => false
					],
				],
				'yAxes' => [
					[
						'gridLines' => [
							'drawBorder' => false,
							'display'    => false,
						],
						'ticks'     => [
							'beginAtZero' => false,
							'display'     => false
						],
					],
				],
			],
		] );
	}

	private function setDataUseVersions(): void {
		$this->dataset( 'Sample', 'pie', $this->StatisticsClientVersions->pluck( 'use' ) )->options( [
			'backgroundColor' => [
				'#3366cc',
				'#dc3912',
				'#ff9900',
				'#109618',
				'#990099',
				'#0099c6',
				'#dd4477',
				'#6a0',
				'#b82e2e',
				'#316395',
				'#994499',
				'#2a9',
				'#f00',
			],
		] );
	}

	private function setLabels(): void {

		$this->labels( $this->StatisticsClientVersions->pluck( 'version' ) );
	}
}
