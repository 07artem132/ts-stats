<?php

namespace App\Charts;

use App\ClientPlatform;
use Illuminate\Support\Collection;
use ConsoleTVs\Charts\Classes\Chartjs\Chart;
use Illuminate\Support\Facades\Redis;

class StatisticTopClientPlatformMonthCharts extends Chart {
	private $StatisticsClientPlatform, $redis;

	/**
	 * Initializes the chart.
	 *
	 * @return void
	 */
	public function __construct() {
		parent::__construct();
		$this->redis                    = Redis::connection();
		$this->StatisticsClientPlatform = $this->getStatistics();
		$this->setDataUsePlatform();
		$this->setLabels();
		$this->setOptions();
	}

	private function getStatistics(): Collection {
		$statistics = collect();

		$result = $this->redis->command( 'HGETALL', [
			config( 'cache.prefix' ) . ':clientPlatform'
		] );

		if ( empty( $result ) ) {
			$result = ClientPlatform::all()->sortBy( 'id' )->pluck( 'id' );
		} else {
			$result = array_flip( $result );
			ksort( $result );
			$result = array_flip( $result );
		}

		foreach ( $result as $item ) {
			$response = $this->redis->command( 'hget', [
				config( 'cache.prefix' ) . ':StatisticsClientPlatform',
				$item
			] );

			if ( $response === false ) {
				$response = (int) ClientPlatform::findOrFail( $item )->clientUsePlatformMonth();

				$this->redis->command( 'hset', [
					config( 'cache.prefix' ) . ':StatisticsClientPlatform',
					$item,
					$response
				] );
			}

			$statistics[] = [
				'use' => (int) $response
			];
		}

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
				'text'    => 'Платформы клиентов'
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

	private function setDataUsePlatform(): void {
		$this->dataset( 'Sample', 'pie', $this->StatisticsClientPlatform->pluck( 'use' ) )->options( [
			'backgroundColor' => [
				'#ff0000',
				'#36c',
				'#109618',
				'#ff9900',
				'#dc3912',
			],
		] );
	}


	private function setLabels(): void {
		$result = array_flip( $this->redis->command( 'HGETALL', [
			config( 'cache.prefix' ) . ':clientPlatform'
		] ) );

		if ( empty( $result ) ) {
			$result = ClientPlatform::select( [ 'platform' ] )->get()->pluck( 'platform' );
		}
		ksort( $result );
		$this->labels( array_values( $result ) );
	}
}
