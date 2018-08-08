<?php

namespace App\Task;

use App\Instance;
use App\Services\TeamSpeak3\TeamSpeak;
use App\Jobs\VirtualServerStatisticsCollections;

/**
 * Class VirtualServersStatisticsCollectionsTask
 * @package Api\Task
 */
class VirtualServersStatisticsCollectionsTask {

	function CronCallback() {
		$servers = Instance::Active()->get();

		foreach ( $servers as $server ) {
			VirtualServerStatisticsCollections::dispatch( $server->id );
		}
	}
}