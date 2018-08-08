<?php

namespace App\Charts;

use App\Instance;
use App\StatisticInstance;
use ConsoleTVs\Charts\Classes\Chartjs\Chart;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Redis;

class StatisticsInstancesRealTimeCharts extends Chart {
	/**
	 * @var StatisticInstance
	 */
	private $StatisticsTeamspeakInstances;

	/**
	 * Initializes the chart.
	 *
	 * @return void
	 */
	public function __construct() {
		parent::__construct();
		$this->setOptions();
		$this->setLabels();
		$this->load( url( '/charts/instance/real-time' ) );
	}

	public function response() {
		$this->StatisticsTeamspeakInstances = $this->getStatistics();
		$this->setDataVirtualServersOnline();
		$this->setDataUserOnline();
		$this->setDataSlotsUsage();

		return $this->api();
	}

	private function getStatistics(): Collection {
		$statistics         = collect();
		$instances          = Instance::all();
		$redis              = Redis::connection();
		$statisticInstances = collect();

		for ( $i = 0; $i < $instances->count(); $i ++ ) {
			$len = $redis->command( 'LLEN', [
				config( 'cache.prefix' ) . ':instance:' . $instances[ $i ]->id . ':stat:real_time'
			] );

			$statisticInstance = $redis->command( 'LRANGE', [
				config( 'cache.prefix' ) . ':instance:' . $instances[ $i ]->id . ':stat:real_time',
				0,
				$len
			] );

			for ( $j = 0; $j < count( $statisticInstance ); $j ++ ) {
				$statisticInstances->push( unserialize( $statisticInstance[ $j ] ) );
			}
		}

		for ( $i = 0; $i < $statisticInstances->count(); $i ++ ) {
			$createdDate = date( "H:i", $statisticInstances[ $i ]->created_at->getTimestamp() );

			if ( ! $statistics->has( $createdDate ) ) {
				$statistics[ $createdDate ]                 = collect();
				$statistics[ $createdDate ]->users_online   = $statisticInstances[ $i ]->users_online;
				$statistics[ $createdDate ]->servers_runing = $statisticInstances[ $i ]->servers_runing;
				$statistics[ $createdDate ]->slots_usage    = $statisticInstances[ $i ]->slots_usage;
				continue;
			}

			$statistics[ $createdDate ]->users_online   += $statisticInstances[ $i ]->users_online;
			$statistics[ $createdDate ]->servers_runing += $statisticInstances[ $i ]->servers_runing;
			$statistics[ $createdDate ]->slots_usage    += $statisticInstances[ $i ]->slots_usage;
		}

		for ( $i = 59; $i > 0; $i -- ) {
			$createdDate = date( 'H:i', time() - ( $i * 60 ) );
			if ( ! $statistics->has( $createdDate ) ) {
				$statistics[ $createdDate ]                 = collect();
				$statistics[ $createdDate ]->users_online   = 0;
				$statistics[ $createdDate ]->servers_runing = 0;
				$statistics[ $createdDate ]->slots_usage    = 0;
			}
		}

		$statistics = $statistics->sortKeys();

		return $statistics;
	}

	private function setOptions(): void {
		$this->options( [
			'title'    => [
				'display' => true,
				'text'    => 'Статистика за последние 60 минут'
			],
			'tooltips' => [
				'enabled'  => true,
				'mode'     => 'x',
				'position' => 'nearest',
			],
			'legend'   => [
				'display' => true
			],
			'scales'   => [
				'yAxes' => [
					[
						'ticks' => [
							'display' => false,
						],
					],
				],
			],
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
		for ( $i = 59; $i > 0; $i -- ) {
			$Labels[] = date( 'H:i', time() - ( $i * 60 ) );
		}

		$this->labels( $Labels );
	}
}
