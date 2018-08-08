<?php
/**
 * Created by PhpStorm.
 * User: Artem
 * Date: 13.07.2018
 * Time: 20:50
 */

namespace App\Task;

use App\Instance;
use App\Jobs\InstanceStatisticsCollections;

class InstanceStatisticsCollectionsTask {
	function CronCallback() {
		$servers = Instance::Active()->get();
		foreach ( $servers as $server ) {
			InstanceStatisticsCollections::dispatch( $server->id );
		}
	}

}