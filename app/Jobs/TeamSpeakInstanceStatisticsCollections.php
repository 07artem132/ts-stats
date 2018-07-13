<?php
/**
 * Created by PhpStorm.
 * User: Artem
 * Date: 13.07.2018
 * Time: 20:51
 */

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use App\StatisticsTeamspeakInstances;
use App\Services\TeamSpeak3\TeamSpeak;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class TeamSpeakInstanceStatisticsCollections implements ShouldQueue {
	use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

	private $instance_id, $ts3con;

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
		$ts3con = new TeamSpeak( $this->instance_id );

		$hostinfo = $ts3con->hostinfo();
		$ts3con->logout();

		$db                = new StatisticsTeamspeakInstances;
		$db->instance_id   = $this->instance_id;
		$db->slots_usage    = (int) $hostinfo[0]['virtualservers_total_maxclients'];
		$db->servers_runing = (int) $hostinfo[0]['virtualservers_running_total'];
		$db->users_online   = (int) $hostinfo[0]['virtualservers_total_clients_online'];
		$db->save();
	}
}
