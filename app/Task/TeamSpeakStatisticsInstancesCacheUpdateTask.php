<?php
/**
 * Created by PhpStorm.
 * User: Artem
 * Date: 07.10.2017
 * Time: 18:28
 */

namespace App\Task;

use App\Jobs\TeamSpeakStatisticsInstancesCacheUpdateDay;
use App\Jobs\TeamSpeakStatisticsInstancesCacheUpdateMonth;
use App\Jobs\TeamSpeakStatisticsInstancesCacheUpdateWeek;
use App\Jobs\TeamSpeakStatisticsInstancesCacheUpdateYear;
use App\TeamspeakInstances;
use App\Jobs\TeamSpeakStatisticsInstancesCacheUpdateRealTime;

/**
 * Class TeamSpeakStatisticsInstancesCacheUpdateTask
 * @package Api\Task
 */
class TeamSpeakStatisticsInstancesCacheUpdateTask {

	function CronCallback() {
		$servers = TeamspeakInstances::Active()->get();

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
		TeamSpeakStatisticsInstancesCacheUpdateYear::dispatch( $instance_id );
	}

	function CacheMonth( $instance_id ) {
		TeamSpeakStatisticsInstancesCacheUpdateMonth::dispatch( $instance_id );
	}

	function CacheWeek( $instance_id ) {
		TeamSpeakStatisticsInstancesCacheUpdateWeek::dispatch( $instance_id );
	}

	function CacheDay( $instance_id ) {
		TeamSpeakStatisticsInstancesCacheUpdateDay::dispatch( $instance_id );
	}

	function CacheRealTime( $instance_id ) {
		TeamSpeakStatisticsInstancesCacheUpdateRealTime::dispatch( $instance_id );
	}
}