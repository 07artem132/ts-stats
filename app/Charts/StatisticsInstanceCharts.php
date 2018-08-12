<?php

namespace App\Charts;

use App\Instance;
use ConsoleTVs\Charts\Classes\Chartjs\Chart;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Redis;

class StatisticsInstanceCharts extends Chart {
	private $StatisticsTeamspeakInstances;
	private $redis;

	/**
	 * StatisticsInstanceCharts constructor.
	 *
	 * @param int|null $instance_id
	 * @param int|null $statistics_type
	 */
	public function __construct( ?int $instance_id = null, int $statistics_type = 1 ) {
		parent::__construct();
		$this->redis                        = Redis::connection();
		$this->StatisticsTeamspeakInstances = $this->getStatistics( $instance_id, $statistics_type );
		$this->setOptions();
		$this->setLabels();
		$this->setDataVirtualServersOnline();
		$this->setDataUserOnline();
		$this->setDataSlotsUsage();
	}


	private function getStatistics( int $instance_id = null, $statistics_type ): Collection {
		$statistics         = collect();
		$statisticInstances = collect();

		if ( $instance_id === null ) {
			$instances = Instance::all();
		} else {
			$instances = collect( [
				0 => collect( [ 'id' => $instance_id ] )
			] );
		}

		switch ( $statistics_type ) {
			case 1:
				$statistics_type = 'real_time';
				break;
			case 2:
				$statistics_type = 'day';
				break;
			case 3:
				$statistics_type = 'Week';
				break;
			case 4:
				$statistics_type = 'Month';
				break;
			case 5:
				$statistics_type = 'Year';
				break;
		}

		for ( $i = 0; $i < $instances->count(); $i ++ ) {
			$len               = $this->redis->command( 'LLEN', [
				config( 'cache.prefix' ) . ':instance:' . $instances[ $i ]['id'] . ':stat:' . $statistics_type
			] );
			$statisticInstance = $this->redis->command( 'LRANGE', [
				config( 'cache.prefix' ) . ':instance:' . $instances[ $i ]['id'] . ':stat:' . $statistics_type,
				0,
				$len
			] );

			for ( $j = 0; $j < count( $statisticInstance ); $j ++ ) {
				$statisticInstances->push( unserialize( $statisticInstance[ $j ] ) );
			}
		}

		for ( $i = 0; $i < $statisticInstances->count(); $i ++ ) {
			$createdDate = date( "d.m.y H:i", $statisticInstances[ $i ]->created_at->getTimestamp() );

			if ( ! $statistics->has( $createdDate ) ) {
				$statistics[ $createdDate ]                 = collect();
				$statistics[ $createdDate ]->users_online   = $statisticInstances[ $i ]->users_online;
				$statistics[ $createdDate ]->servers_runing = $statisticInstances[ $i ]->servers_runing;
				$statistics[ $createdDate ]->slots_usage    = $statisticInstances[ $i ]->slots_usage;
				$statistics[ $createdDate ]->create_at      = $createdDate;
				continue;
			}

			$statistics[ $createdDate ]->users_online   += $statisticInstances[ $i ]->users_online;
			$statistics[ $createdDate ]->servers_runing += $statisticInstances[ $i ]->servers_runing;
			$statistics[ $createdDate ]->slots_usage    += $statisticInstances[ $i ]->slots_usage;
			$statistics[ $createdDate ]->create_at      = $createdDate;

		}

		$statistics = $statistics->sortKeys();

		return $statistics;
	}

	private function setOptions(): void {
		$this->options( [
			'title'               => [
				'display' => true,
				'text'    => 'Общая статистика по инстансу'
			],
			'tooltips'            => [
				'enabled'  => true,
				'mode'     => 'x',
				'position' => 'nearest',
			],
			'legend'              => [
				'display' => true
			],
			'responsive'          => true,
			'maintainAspectRatio' => false,
			'scales'              => [
				'xAxes' => [
					[
						'type'           => "time",
						'time'           => [
							'format'        => 'DD.MM.YYYY HH:mm',
							'tooltipFormat' => 'DD.MM.YYYY HH:mm',
							 'displayFormats' => [
								'millisecond' => 'h:mm:ss.SSS a',
								'second'      => 'h:mm:ss a',
								'minute'      => 'h:mm a',
								'hour'        => 'HH часов',
								'day'         => 'M DD',
								'week'        => 'll',
								'month'       => 'M YYYY',
								'quarter'     => '[Q]Q - YYYY',
								'year'        => 'YYYY'
							],
						],

						'gridLines'      => [
							'zeroLineColor' => "rgba(0,255,0,1)"
						],
						'scaleLabel'     => [
							'display'     => true,
							'labelString' => 'Дата'
						],
						'ticks'          => [
							'maxRotation' => 0,
							'source'      => 'auto',
							'reverse'     => false
						]
					]
				],
				'yAxes' => [
					[
						'gridLines'  => [
							//'zeroLineColor' => "rgba(0,255,0,1)"
						],
						'scaleLabel' => [
							'display'     => true,
							'labelString' => 'Значения'
						],
						'ticks'      => [
							'reverse' => false,
							'source'  => 'auto',
						]
					]
				]
			],
			'pan'                 => [
				'enabled' => true,
				'mode'    => 'xy'
			],
			'zoom'                => [
				'enabled' => true,
				'mode'    => 'xy',
				'limits'  => [
					'max' => 10,
					'min' => 0.5
				]
			]
		] );
	}

	private function setDataUserOnline(): void {
		$this->dataset( 'Пользователи онлайн', 'line', $this->StatisticsTeamspeakInstances->pluck( 'users_online' )->all() )->options( [
			'borderColor'     => 'rgb(131, 209, 73)',
			'backgroundColor' => 'rgb(213, 230, 201)',
		] );
	}

	private function setDataVirtualServersOnline(): void {
		$this->dataset( 'Виртуальные сервера', 'line', $this->StatisticsTeamspeakInstances->pluck( 'servers_runing' )->all() )->options( [
			'borderColor'     => 'rgb(26, 77, 128)',
			'backgroundColor' => 'rgb(158, 184, 179)',
		] );
	}

	private function setDataSlotsUsage(): void {
		$this->dataset( 'Использовано слотов', 'line', $this->StatisticsTeamspeakInstances->pluck( 'slots_usage' )->all() )->options( [
			'borderColor'     => '#cccccc',
			'backgroundColor' => 'rgba(240, 240, 240, 0.8)',
		] );
	}

	private function setLabels(): void {
		$this->labels( $this->StatisticsTeamspeakInstances->pluck( 'create_at' ) );
	}
}
