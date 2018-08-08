<?php

namespace App\Charts;

use App\ClientVersion;
use App\StatisticInstance;
use Illuminate\Support\Collection;
use ConsoleTVs\Charts\Classes\Chartjs\Chart;
use \Debugbar;

class StatisticUsageClientVersionMonthCharts extends Chart {
	/**
	 * @var StatisticInstance
	 */
	private $StatisticsClientVersions;

	/**
	 * Initializes the chart.
	 *
	 * @return void
	 */
	public function __construct() {
		parent::__construct();

		$this->setOptions();
		$this->setLabels();
		$this->load( url( '/charts/client/version/month' ) );
	}

	function response() {
		Debugbar::startMeasure( 'Форматирование и загрузка статистики по версииям клиентов' );
		$this->StatisticsClientVersions = $this->getStatistics();
		Debugbar::stopMeasure( 'Форматирование и загрузка статистики по версииям клиентов' );
		$this->setDataUseVersions();

		return $this->api();
	}

	private function getStatistics(): Collection {
		$result     = ClientVersion::select( [ 'id' ] )->get();
		$statistics = collect();

		foreach ( $result as $item ) {
			$statistics[] = [
				'use'     => $item->clientUseVersionMonth(),
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
			/*	'#507e32',
				'#2f528f',
				'#bc8c00',
				'#787878',
				'#ae5a21',
				'#41719c',
				'#aaaaaa',
				'#c9dbc1',
				'#c0c9e4',
				'#ffe2bc',
				'#d8d8d8',
				'#f6ccbe',
				'#c4d5eb',
				'#a4c0e3',
				'#f3b29a',
				'#c6c6c6',
				'#ffd695',
				'#9eadd8',
				'#acca9e',
				'#dadada',
				'#5694cb',
				'#e2772e',
				'#9d9d9d',
				'#ffd695',
				'#5f5f5f',
				'#212121',
				'#ffc859',
				'#88b76e',*/
			],
		] );
	}


	private function setLabels(): void {
		$result     = ClientVersion::select( [ 'id', 'major', 'minor', 'patch' ] )->get();
		$statistics = collect();

		foreach ( $result as $item ) {
			$statistics[] = [
				'version' => $item->major . '.' . $item->minor . '.' . $item->patch
			];
		}

		$this->labels( $statistics->pluck( 'version' ) );
	}
}
