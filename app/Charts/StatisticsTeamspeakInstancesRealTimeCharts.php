<?php

namespace App\Charts;

use ConsoleTVs\Charts\Classes\Chartjs\Chart;
use App\StatisticsTeamspeakInstances;
use Carbon\Carbon;

class StatisticsTeamspeakInstancesRealTimeCharts extends Chart {
	/**
	 * @var StatisticsTeamspeakInstances
	 */
	private $StatisticsTeamspeakInstances;

	/**
	 * Initializes the chart.
	 *
	 * @return void
	 */
	public function __construct() {
		parent::__construct();
		$this->StatisticsTeamspeakInstances = StatisticsTeamspeakInstances::StatRealtime();
		$this->setLabels();
		$this->setOptions();
		$this->setDataVirtualServersOnline();
		$this->setDataUserOnline();
		$this->setDataSlotsUsage();

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
		$Labels = $this->StatisticsTeamspeakInstances->pluck( 'created_at' )->all();

		array_walk( $Labels, function ( Carbon &$val, int $key ) {
			$val = $val->format( 'H:i' );
		} );

		$this->labels( $Labels );
	}
}
