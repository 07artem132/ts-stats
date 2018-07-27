<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use App\Traits\RestHelperTrait;
use App\Exceptions\InvalidJSON;
use Illuminate\Support\Facades\Redis;
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
//		$redis = Redis::connection();

		try {
			$InitialSearchDate = $this->JsonDecodeAndValidate( Redis::lpop( "ts:stat:$this->instance_id:day" ) )->created_at;
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
			Redis::lpush( "ts:stat:$this->instance_id:day", $item->toJson() );
			Redis::ltrim( "ts:stat:$this->instance_id:day", 0, 288 );
		}
	}
}
