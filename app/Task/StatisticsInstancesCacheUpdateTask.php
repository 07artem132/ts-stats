<?php
/**
 * Created by PhpStorm.
 * User: Artem
 * Date: 07.10.2017
 * Time: 18:28
 */

namespace App\Task;

use App\Jobs\StatisticsInstancesCacheUpdateDay;
use App\Jobs\StatisticsInstancesCacheUpdateMonth;
use App\Jobs\StatisticsInstancesCacheUpdateWeek;
use App\Jobs\StatisticsInstancesCacheUpdateYear;
use App\Instance;
use App\Jobs\StatisticsInstancesCacheUpdateRealTime;

/**
 * Class StatisticsInstancesCacheUpdateTask
 * @package Api\Task
 */
class StatisticsInstancesCacheUpdateTask {

	function CronCallback() {
		$servers = Instance::Active()->get();

		foreach ( $servers as $server ) {
			$this->CacheUpdate( $server->id );
		}
	}

	function CacheUpdate( $instance_id ) {
		$this->CacheRealTime( $instance_id );
		$this->CacheDay( $instance_id );
		$this->CacheWeek( $instance_id );
		$this->CacheMonth( $instance_id );
		$this->CacheYear( $instance_id );
	}

	function CacheYear( $instance_id ) {
		StatisticsInstancesCacheUpdateYear::dispatch( $instance_id );
	}

	function CacheMonth( $instance_id ) {
		StatisticsInstancesCacheUpdateMonth::dispatch( $instance_id );
	}

	function CacheWeek( $instance_id ) {
		StatisticsInstancesCacheUpdateWeek::dispatch( $instance_id );
	}

	function CacheDay( $instance_id ) {
		StatisticsInstancesCacheUpdateDay::dispatch( $instance_id );
	}

	function CacheRealTime( $instance_id ) {
		StatisticsInstancesCacheUpdateRealTime::dispatch( $instance_id );
	}
}