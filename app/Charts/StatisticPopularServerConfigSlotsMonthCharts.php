<?php

namespace App\Charts;

use App\StatisticVirtualServer;
use Illuminate\Support\Collection;
use ConsoleTVs\Charts\Classes\Chartjs\Chart;
use Illuminate\Support\Facades\Redis;

class StatisticPopularServerConfigSlotsMonthCharts extends Chart {
	private $StatisticPopularServerConfigSlots;
	private $redis;

	/**
	 * Initializes the chart.
	 *
	 * @return void
	 */
	public function __construct(?int $instance_id = null) {
		parent::__construct();
		$this->redis = Redis::connection();

		$this->StatisticPopularServerConfigSlots = $this->getStatistics($instance_id);
		$this->setDataPopularServerConfigSlots();
		$this->setLabels();
		$this->setOptions();
	}

	private function getStatistics(int $instance_id = null): Collection {
		$result = unserialize( $this->redis->command( 'get', [
			config( 'cache.prefix' ) . ':StatisticsPopularServerSlotsMonth'
		] ) );

		if ( $result === false ) {
			$result = StatisticVirtualServer::PopularServerSlotsMonth()->get()->sortByDesc( 'count' );
			$this->redis->command( 'set', [
				config( 'cache.prefix' ) . ':StatisticsPopularServerSlotsMonth',
				serialize( $result )
			] );
		}

		foreach ( $result as &$item ) {
			$item->count = $item['slot_usage'] * $item['count'];
		}

		$other = $result->splice( 7 );

		$result[] = collect( [
			'slot_usage' => 'Остальные',
			'count'      => (int) $other->sum( 'count' )
		] );

		return $result;
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
				'text'    => 'Сумма используемых слотов в разбивке по кол-ву слотов на виртуальном сервере'
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

	private function setDataPopularServerConfigSlots(): void {
		$this->dataset( 'Sample', 'pie', $this->StatisticPopularServerConfigSlots->pluck( 'count' ) )->options( [
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
		$this->labels( $this->StatisticPopularServerConfigSlots->pluck( 'slot_usage' ) );
	}
}
