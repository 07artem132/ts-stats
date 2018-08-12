<?php

namespace App\Jobs;

use Illuminate\Support\Facades\Redis;
use Illuminate\Bus\Queueable;
use App\Traits\RestHelperTrait;
use App\ClientContry;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class StatisticsVirtualServerCountryCacheUpdateWeek implements ShouldQueue {
	use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, RestHelperTrait;

	private $virtual_servers_id, $instance_id, $redis;

	/**
	 * StatisticsVirtualServerCountryCacheUpdateHour constructor.
	 *
	 * @param int $instance_id
	 * @param int $virtual_servers_id
	 */
	public function __construct( int $instance_id, int $virtual_servers_id ) {
		$this->virtual_servers_id = $virtual_servers_id;
		$this->instance_id        = $instance_id;
	}

	/**
	 * Execute the job.
	 *
	 * @return void
	 */
	public function handle() {
		$this->redis              = Redis::connection();

		foreach ( ClientContry::all() as $country ) {
			$ttl = $this->redis->command( 'ttl', [
				config( 'cache.prefix' ) . ':instance:' . $this->instance_id . ':virtualServer:' . $this->virtual_servers_id . ':country:' . $country->id . ':week'
			] );

			if ( 512 > $ttl ) {
				$this->redis->command( 'set', [
					config( 'cache.prefix' ) . ':instance:' . $this->instance_id . ':virtualServer:' . $this->virtual_servers_id . ':country:' . $country->id . ':week',
					$country->client()->VirtualServersId( $this->virtual_servers_id )->Week()->UniqueClientsCount(),
					[
						'EX' => 259200 //60*60*24*3
					]
				] );
			}
		}

	}

	public function tags() {
		return [ 'cache update week', 'instance:' . $this->instance_id, 'virtual server:' . $this->virtual_servers_id ];
	}

}
