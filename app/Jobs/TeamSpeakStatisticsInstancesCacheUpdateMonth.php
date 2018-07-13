<?php

namespace Api\Jobs;

use Redis;
use Illuminate\Bus\Queueable;
use Api\Traits\RestHelperTrait;
use Api\Exceptions\InvalidJSON;
use Api\StatisticsTeamspeakInstances;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class TeamSpeakStatisticsInstancesCacheUpdateMonth implements ShouldQueue {
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
		$redis = Redis::connection();

		try {
			$InitialSearchDate = $this->JsonDecodeAndValidate( $redis->lpop( "ts:stat:$this->instance_id:Month" ) )->created_at;
			if ( ( time() - strtotime( $InitialSearchDate ) ) < 3600 ) {
				return;
			}
		} catch ( InvalidJSON $e ) {
			$InitialSearchDate = null;
		}

		if ( ! empty( $InitialSearchDate ) ) {
			$data = StatisticsTeamspeakInstances::InstanceId( $this->instance_id )->StatMonth( $InitialSearchDate )->HourAvage()->get();
		} else {
			$data = StatisticsTeamspeakInstances::InstanceId( $this->instance_id )->StatMonth()->HourAvage()->get();
		}

		foreach ( $data as $item ) {
			$redis->lpush( "ts:stat:$this->instance_id:Month", $item->toJson() );
			$redis->ltrim( "ts:stat:$this->instance_id:Month", 0, 288 );
		}

	}
}
