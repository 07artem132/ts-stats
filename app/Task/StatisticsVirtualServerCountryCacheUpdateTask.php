<?php
/**
 * Created by PhpStorm.
 * User: Artem
 * Date: 11.08.2018
 * Time: 18:28
 */

namespace App\Task;

use App\Instance;
use App\Jobs\StatisticsVirtualServerCountryCacheUpdateMonth;
use App\Jobs\StatisticsVirtualServerCountryCacheUpdateWeek;
use App\Jobs\StatisticsVirtualServerCountryCacheUpdateYear;

/**
 * Class StatisticsVirtualServerCountryCacheUpdateTask
 * @package Api\Task
 */
class StatisticsVirtualServerCountryCacheUpdateTask {

	function CronCallback() {
		$Instances = Instance::Active()->get();

		foreach ( $Instances as $instance ) {
			foreach ( $instance->InstanceVirtualServers()->get( [ 'virtual_servers_id' ] ) as $virtualServer ) {
				$this->CacheUpdate( $instance->id, $virtualServer->virtual_servers_id );
			}
		}
	}

	function CacheUpdate( $instance_id, $virtual_servers_id ) {
		$this->CacheWeek( $instance_id, $virtual_servers_id );
		$this->CacheMonth( $instance_id, $virtual_servers_id );
		$this->CacheYear( $instance_id, $virtual_servers_id );
	}

	function CacheYear( $instance_id, $virtual_servers_id ) {
		StatisticsVirtualServerCountryCacheUpdateYear::dispatch( $instance_id, $virtual_servers_id );
	}

	function CacheMonth( $instance_id, $virtual_servers_id ) {
		StatisticsVirtualServerCountryCacheUpdateMonth::dispatch( $instance_id, $virtual_servers_id );
	}

	function CacheWeek( $instance_id, $virtual_servers_id ) {
		StatisticsVirtualServerCountryCacheUpdateWeek::dispatch( $instance_id, $virtual_servers_id );
	}


}