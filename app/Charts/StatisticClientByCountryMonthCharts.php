<?php

namespace App\Charts;

use App\ClientContry;
use App\ClientVersion;
use App\StatisticInstance;
use Illuminate\Support\Collection;
use ConsoleTVs\Charts\Classes\Chartjs\Chart;
use \Debugbar;

class StatisticClientByCountryMonthCharts extends Chart {
	/**
	 * @var StatisticInstance
	 */
	private $StatisticsClientCountry;

	/**
	 * Initializes the chart.
	 *
	 * @return void
	 */
	public function __construct() {
		parent::__construct();

		$this->setOptions();
		$this->setLabels();
		$this->load( url( '/charts/client/country/month' ) );
	}

	function response() {
		Debugbar::startMeasure( 'Форматирование и загрузка статистики по странам клиентов' );
		$this->StatisticsClientCountry = $this->getStatistics();
		Debugbar::stopMeasure( 'Форматирование и загрузка статистики по странам клиентов' );
		$this->setDataClientCountry();

		return $this->api();
	}

	private function getStatistics(): Collection {
		$result     = ClientContry::select( [ 'id' ] )->get();
		$statistics = collect();

		foreach ( $result as $item ) {
			$statistics[] = [
				'fromTheCountry'     => $item->clientFromTheCountryMonth(),
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
		$this->dataset( 'Sample', 'pie', $this->StatisticsClientCountry->pluck( 'fromTheCountry' ) )->options( [
			'backgroundColor' => [
			/*	'#990000',
				'#c30000',
				'#ee0000',
				'#ff1a00',
				'#ff4600',
				'#ff7300',
				'#ff9f00',
				'#ffcb00',
				'#fff700',
				'#e3f408',
				'#c3e711',
				'#a3da1b',
				'#83cd25',
				'#63c02e',
				'#42b338',
				'#22a642',
				'#029a4b',
				'#0c876a',
				'#1a758a',
				'#2863aa',
				'#3650cb',
				'#443eeb',
				'#612aff',
				'#9615ff',
				'#cc00ff',
				'#5da5a1',
				'#c45331',
				'#e79609',
				'#f6e84a',
				'#b1a2a7',
				'#c9a784',
				'#8c7951',
				'#d8cdb7',
				'#086553',
				'#f7d87b',
				'#016484',*/

			],
		] );
	}


	private function setLabels(): void {
		$result = ClientContry::select( [ 'country' ] )->get();

		$this->labels( $result->pluck( 'country' ) );
	}
}
