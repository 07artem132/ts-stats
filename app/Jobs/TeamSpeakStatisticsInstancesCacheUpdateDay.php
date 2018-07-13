<?php

namespace Api\Jobs;

use Redis;
use Illuminate\Bus\Queueable;
use App\Traits\RestHelperTrait;
use App\Exceptions\InvalidJSON;
use App\StatisticsTeamspeakInstances;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class TeamSpeakStatisticsInstancesCacheUpdateDay implements ShouldQueue {
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
			$InitialSearchDate = $this->JsonDecodeAndValidate( $redis->lpop( "ts:stat:$this->instance_id:day" ) )->created_at;
			if ( ( time() - strtotime( $InitialSearchDate ) ) < 300 ) {
				return;
			}
		} catch ( InvalidJSON $e ) {
			$InitialSearchDate = null;
		}

		if ( ! empty( $InitialSearchDate ) ) {
			$data = StatisticsTeamspeakInstances::InstanceId( $this->instance_id )->StatDay( $InitialSearchDate )->FiveMinutesAvage()->get();
		} else {
			$data = StatisticsTeamspeakInstances::InstanceId( $this->instance_id )->StatDay()->FiveMinutesAvage()->get();
		}

		foreach ( $data as $item ) {
			$redis->lpush( "ts:stat:$this->instance_id:day", $item->toJson() );
			$redis->ltrim( "ts:stat:$this->instance_id:day", 0, 288 );
		}
	}
}
