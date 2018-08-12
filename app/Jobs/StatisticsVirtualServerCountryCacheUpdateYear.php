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

class StatisticsVirtualServerCountryCacheUpdateYear implements ShouldQueue {
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
				config( 'cache.prefix' ) . ':instance:' . $this->instance_id . ':virtualServer:' . $this->virtual_servers_id . ':country:' . $country->id . ':year'
			] );

			if ( 1024 > $ttl ) {
				$this->redis->command( 'set', [
					config( 'cache.prefix' ) . ':instance:' . $this->instance_id . ':virtualServer:' . $this->virtual_servers_id . ':country:' . $country->id . ':year',
					$country->client()->VirtualServersId( $this->virtual_servers_id )->Year()->UniqueClientsCount(),
					[
						'EX' => 604800 //60*60*24*7
					]
				] );
			}
		}

	}

	public function tags() {
		return [ 'cache update year', 'instance:' . $this->instance_id, 'virtual server:' . $this->virtual_servers_id ];
	}

}
