<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use App\Services\TeamSpeak3\TeamSpeak;
use Illuminate\Queue\InteractsWithQueue;
use App\StatisticVirtualServer;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\InstanceVirtualServer;
use Cache;
use Illuminate\Support\Facades\Redis;
use App\VirtualServer;

class VirtualServerStatisticsCollections implements ShouldQueue {
	use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

	private $instance_id, $ts3con;

	/**
	 * Create a new job instance.
	 *
	 * @return void
	 */
	public function __construct( $instance_id ) {
		$this->instance_id = $instance_id;
	}

	/**
	 * @throws \App\Exceptions\InstanceConfigNotFoundException
	 * @throws \App\Exceptions\TeamSpeakInvalidUidException
	 * @throws \Throwable
	 */
	public function handle() {
		$this->ts3con = new TeamSpeak( $this->instance_id );

		try {
			foreach ( $this->ts3con->ReturnConnection()->serverList() as $VirtualServer ) {
				try {
					if ( (string) $VirtualServer['virtualserver_status'] != 'online' ) {
						continue;
					}

					$db                     = new StatisticVirtualServer;
					$db->virtual_servers_id = $this->getVirtualServerID( (string) $VirtualServer['virtualserver_unique_identifier'], (int) $VirtualServer['virtualserver_port'] );
					$db->user_online        = $VirtualServer['virtualserver_clientsonline'];
					$db->slot_usage         = $VirtualServer['virtualserver_maxclients'];
					$db->avg_ping           = $VirtualServer['virtualserver_total_ping'];
					$db->avg_packetloss     = $VirtualServer['virtualserver_total_packetloss_total'];
					$db->saveOrFail();
				} catch ( \Exception $e ) {
					if ( $e->getMessage() === 'server maxclient reached' ) {
						continue;
					}
					throw new \Exception( $e->getMessage() );
				}
			}
		} catch ( \Exception $e ) {
			if ( $e->getMessage() === 'database empty result set' ) {
				$this->ts3con->logout();

				return;
			}
			throw new \Exception( $e->getMessage() );
		}
		$this->ts3con->logout();
	}

	/**
	 * @param string $uid
	 *
	 * @return int
	 */
	public function getVirtualServerID( string $uid, int $port ): int {
		$return = Redis::hget( config( 'cache.prefix' ) . ':instance:' . $this->instance_id . ':VirtualServersList', $uid );

		if ( $return === false ) {
			$VirtualServer = VirtualServer::firstOrCreate( [ 'uid' => $uid, 'port' => $port ] );
			$return        = $VirtualServer->id;
			InstanceVirtualServer::firstOrCreate( [
				'instances_id'       => $this->instance_id,
				'virtual_servers_id' => $VirtualServer->id
			] );
			Redis::hset( config( 'cache.prefix' ) . ':instance:' . $this->instance_id . ':VirtualServersList', $uid, $return );
		}

		return $return;
	}

	public function tags() {
		return [ 'virtual server statistics collections', 'instance:' . $this->instance_id ];
	}

}
