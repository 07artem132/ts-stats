<?php
/**
 * Created by PhpStorm.
 * User: Artem
 * Date: 13.07.2018
 * Time: 20:50
 */

namespace App\Task;

use App\TeamspeakInstances;
use App\Jobs\TeamSpeakInstanceStatisticsCollections;

class TeamSpeakInstanceStatisticsCollectionsTask {
	function CronCallback() {
		$servers = TeamspeakInstances::Active()->get();
		foreach ( $servers as $server ) {
			TeamSpeakInstanceStatisticsCollections::dispatch( $server->id );
		}
	}

}