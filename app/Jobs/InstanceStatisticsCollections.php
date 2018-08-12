<?php
/**
 * Created by PhpStorm.
 * User: Artem
 * Date: 13.07.2018
 * Time: 20:51
 */

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use App\StatisticInstance;
use App\Services\TeamSpeak3\TeamSpeak;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class InstanceStatisticsCollections implements ShouldQueue {
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
	 * @throws \App\Exceptions\InstanceConfigNotFoundException
	 * @throws \App\Exceptions\TeamSpeakInvalidUidException
	 * @throws \Throwable
	 */
	public function handle() {
		$this->ts3con = new TeamSpeak( $this->instance_id );

		$hostinfo = $this->ts3con->hostinfo();
		$this->ts3con->logout();

		$db                 = new StatisticInstance;
		$db->instances_id   = $this->instance_id;
		$db->slots_usage    = (int) $hostinfo[0]['virtualservers_total_maxclients'];
		$db->servers_runing = (int) $hostinfo[0]['virtualservers_running_total'];
		$db->users_online   = (int) $hostinfo[0]['virtualservers_total_clients_online'];
		$db->saveOrFail();
	}

	public function tags() {
		return [ 'Statistic collections instance', 'instance:' . $this->instance_id ];
	}

}
