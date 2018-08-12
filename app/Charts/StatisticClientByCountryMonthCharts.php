<?php

namespace App\Charts;

use App\ClientContry;
use Illuminate\Support\Collection;
use ConsoleTVs\Charts\Classes\Chartjs\Chart;
use Illuminate\Support\Facades\Redis;

class StatisticClientByCountryMonthCharts extends Chart {

	private $StatisticsClientCountry;

	/**
	 * Initializes the chart.
	 *
	 * @return void
	 */
	public function __construct() {
		parent::__construct();
		$this->redis                   = Redis::connection();
		$this->StatisticsClientCountry = $this->getStatistics();
		$this->setDataClientCountry();
		$this->setLabels();
		$this->setOptions();
	}

	private function getStatistics(): Collection {
		$statistics = collect();

		$result = $this->redis->command( 'HGETALL', [
			config( 'cache.prefix' ) . ':clientCountry'
		] );

		if ( empty( $result ) ) {
			$result = ClientContry::all()->sortBy( 'id' )->pluck( 'id' );
		} else {
			$result = array_flip( $result );
			ksort( $result );
			$result = array_flip( $result );
		}

		foreach ( $result as $item ) {
			$response = $this->redis->command( 'hget', [
				config( 'cache.prefix' ) . ':StatisticsClientCountry',
				$item
			] );
			if ( $response === false ) {
				$response = (int) ClientContry::findOrFail( $item )->clientFromTheCountryMonth();

				$this->redis->command( 'hset', [
					config( 'cache.prefix' ) . ':StatisticsClientCountry',
					$item,
					$response
				] );
			}

			$statistics[] = collect( [
				'country_id' => $item,
				'use'        => (int) $response
			] );
		}

		$statistics   = $statistics->sortByDesc( 'use' );
		$other        = $statistics->splice( 5 );
		$statistics[] = collect( [
			'country' => 'Остальные',
			'use'     => (int) $other->sum( 'use' )
		] );

		$ClientCountry = ClientContry::whereIn( 'id', $statistics->pluck( 'country_id' ) )->get()->getDictionary();

		for ( $i = 0; $i < $statistics->count(); $i ++ ) {
			if ( ! $statistics[ $i ]->has( 'country' ) ) {
				$statistics[ $i ]['country'] = $ClientCountry[ $statistics[ $i ]['country_id'] ]->country;
				unset( $statistics[ $i ]['country_id'] );
			}
		}
		unset($ClientCountry);

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
				'text'    => 'Статистика за последний месяц по странам клиентов'
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

	private function setDataClientCountry(): void {
		$this->dataset( 'Sample', 'pie', $this->StatisticsClientCountry->pluck( 'use' ) )->options( [
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

		$this->labels( $this->StatisticsClientCountry->pluck( 'country' ) );
	}
}
