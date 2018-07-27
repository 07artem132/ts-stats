<?php

namespace App\Jobs;

use Illuminate\Support\Facades\Redis;
use Illuminate\Bus\Queueable;
use App\Traits\RestHelperTrait;
use App\Exceptions\InvalidJSON;
use App\StatisticsTeamspeakInstances;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class TeamSpeakStatisticsInstancesCacheUpdateWeek implements ShouldQueue
{
	use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, RestHelperTrait;

	private $instance_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($instance_id)
    {
	    $this->instance_id = $instance_id;

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
	  //  $redis = Redis::connection();

	    try {
		    $InitialSearchDate = $this->JsonDecodeAndValidate( Redis::lpop( "ts:stat:$this->instance_id:Week" ) )->created_at;
		    if ( ( time() - strtotime( $InitialSearchDate ) ) < 1800 ) {
			    return;
		    }
	    } catch ( InvalidJSON $e ) {
		    $InitialSearchDate = null;
	    }

	    if ( ! empty( $InitialSearchDate ) ) {
		    $data = StatisticsTeamspeakInstances::InstanceId( $this->instance_id )->StatWeek( $InitialSearchDate )->HalfHourAvage()->get();
	    } else {
		    $data = StatisticsTeamspeakInstances::InstanceId( $this->instance_id )->StatWeek()->HalfHourAvage()->get();
	    }

	    foreach ( $data as $item ) {
		    Redis::lpush( "ts:stat:$this->instance_id:Week", $item->toJson() );
		    Redis::ltrim( "ts:stat:$this->instance_id:Week", 0, 288 );
	    }
    }
}
