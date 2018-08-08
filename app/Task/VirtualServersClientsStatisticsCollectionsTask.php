<?php

namespace App\Task;

use App\Instance;
	use App\Jobs\VirtualServerClientsStatisticsCollections;
use App\Services\TeamSpeak3\TeamSpeak;
use App\Jobs\VirtualServerStatisticsCollections;

/**
 * Class VirtualServersStatisticsCollectionsTask
 * @package Api\Task
 */
class VirtualServersClientsStatisticsCollectionsTask {

	function CronCallback() {
		$servers = Instance::Active()->get();

		foreach ( $servers as $server ) {
			VirtualServerClientsStatisticsCollections::dispatch( $server->id );
		}
	}
}