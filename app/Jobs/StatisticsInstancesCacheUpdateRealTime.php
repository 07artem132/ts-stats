<?php

namespace App\Jobs;

use Illuminate\Support\Facades\Redis;
use Illuminate\Bus\Queueable;
use App\Traits\RestHelperTrait;
use App\Exceptions\InvalidJSON;
use App\StatisticInstance;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class StatisticsInstancesCacheUpdateRealTime implements ShouldQueue {
	use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, RestHelperTrait;

	private $instance_id;

	/**
	 * Create a new job instance.
	 *
	 * @return void
	 */
	public function __construct( int $instance_id ) {
		$this->instance_id = $instance_id;
	}

	/**
	 * Execute the job.
	 *
	 * @return void
	 */
	public function handle() {
		$redis    = Redis::connection();
		$response = $redis->command( 'LINDEX', [
			config( 'cache.prefix' ) . ':instance:' . $this->instance_id . ':stat:real_time',
			0
		] );

		if ( $response === false ) {
			$InitialSearchDate = null;
		} else {
			$InitialSearchDate = unserialize( $response )->created_at->getTimestamp();
		}

		if ( ! empty( $InitialSearchDate ) ) {
			$data = StatisticInstance::InstanceId( $this->instance_id )->StatRealtime( $InitialSearchDate )->get();
		} else {
			$data = StatisticInstance::InstanceId( $this->instance_id )->StatRealtime()->get();
		}

		foreach ( $data as $item ) {
			$redis->command( 'lpush', [
				config( 'cache.prefix' ) . ":instance:$this->instance_id:stat:real_time",
				serialize( $item )
			] );
		}
		$redis->command( 'ltrim', [ config( 'cache.prefix' ) . ":instance:$this->instance_id:stat:real_time", 0, 59 ] );

	}

	public function tags() {
		return [ 'cache update real-time', 'instance:' . $this->instance_id ];
	}

}
