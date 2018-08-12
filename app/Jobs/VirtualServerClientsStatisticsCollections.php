<?php

namespace App\Jobs;

use App\Client;
use App\ClientContry;
use App\ClientIpAddress;
use App\ClientNickname;
use App\ClientPlatform;
use App\ClientVersion;
use App\VirtualServer;
use Illuminate\Bus\Queueable;
use App\InstanceVirtualServer;
use Illuminate\Support\Facades\Redis;
use App\StatisticVirtualServerClient;
use Illuminate\Queue\SerializesModels;
use App\Services\TeamSpeak3\TeamSpeak;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class VirtualServerClientsStatisticsCollections implements ShouldQueue {
	use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

	private $instance_id;
	private $ts3con;

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

					foreach ( $VirtualServer->clientlist() as $client ) {
						if ( $client['client_type'] === 1 ) {
							continue;
						}

						$client_country = (string) $client['client_country'];

						if ( empty( $client_country ) ) {
							$client_country = 'None';
						}

						$clientVersion = $this->ClientVersionParse( (string) $client['client_version'] );

						if ( ! is_integer( $clientVersion['minor'] ) || ! is_integer( $clientVersion['patch'] ) ) {
							continue;
						}

						$db                         = new StatisticVirtualServerClient();
						$db->virtual_servers_id     = $this->getVirtualServerID( $VirtualServer['virtualserver_unique_identifier'], (int) $VirtualServer['virtualserver_port'] );
						$db->clients_id             = $this->getClientID( (string) $client['client_unique_identifier'] );
						$db->client_contries_id     = $this->getClientCountryID( $client_country );
						$db->client_nicknames_id    = $this->getClientNicknameID( (string) $client['client_nickname'] );
						$db->client_ip_addresses_id = $this->GetClientIpAddressID( (string) $client['connection_client_ip'] );
						$db->client_platforms_id    = $this->getClientPlatformID( (string) $client['client_platform'] );
						$db->client_versions_id     = $this->GetClientVersionID(
							$clientVersion['major'],
							$clientVersion['minor'],
							$clientVersion['patch'],
							$clientVersion['build']
						);
						$db->saveOrFail();
					}
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
	 * @param int $port
	 *
	 * @return int
	 */
	private function getVirtualServerID( string $uid, int $port ): int {
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

	/**
	 * @param string $uid
	 *
	 * @return int
	 */
	private function getClientID( string $uid ): int {
		$return = Redis::hget( config( 'cache.prefix' ) . ':clients', $uid );

		if ( $return === false ) {
			$Client = Client::firstOrCreate( [ 'uid' => $uid ] );
			$return = $Client->id;
			Redis::hset( config( 'cache.prefix' ) . ':clients', $uid, $return );
		}

		return $return;
	}

	/**
	 * @param string $clientNickname
	 *
	 * @return int
	 */
	private function getClientNicknameID( string $clientNickname ): int {
		$return = Redis::hget( config( 'cache.prefix' ) . ':clientNickname', $clientNickname );

		if ( $return === false ) {
			$ClientNickname = ClientNickname::firstOrCreate( [ 'nickname' => $clientNickname ] );
			$return         = $ClientNickname->id;
			Redis::hset( config( 'cache.prefix' ) . ':clientNickname', $clientNickname, $return );
		}

		return $return;
	}

	/**
	 * @param string $clientCountry
	 *
	 * @return int
	 */
	private function getClientCountryID( string $clientCountry ): int {
		$return = Redis::hget( config( 'cache.prefix' ) . ':clientNickname', $clientCountry );

		if ( $return === false ) {
			$ClientContry = ClientContry::firstOrCreate( [ 'country' => $clientCountry ] );
			$return       = $ClientContry->id;
			Redis::hset( config( 'cache.prefix' ) . ':clientCountry', $clientCountry, $return );
		}

		return $return;

	}

	/**
	 * @param string $clientPlatform
	 *
	 * @return int
	 */
	private function getClientPlatformID( string $clientPlatform ): int {
		$return = Redis::hget( config( 'cache.prefix' ) . ':clientPlatform', $clientPlatform );

		if ( $return === false ) {
			$ClientPlatform = ClientPlatform::firstOrCreate( [ 'platform' => $clientPlatform ] );
			$return         = $ClientPlatform->id;
			Redis::hset( config( 'cache.prefix' ) . ':clientPlatform', $clientPlatform, $return );
		}

		return $return;
	}

	/**
	 * @param int $major
	 * @param int $minor
	 * @param int $patch
	 * @param int $build
	 *
	 * @return int
	 */
	private function GetClientVersionID( int $major, int $minor, int $patch, int $build ): int {
		$return = Redis::hget( config( 'cache.prefix' ) . ':clientVersion', $major . $minor . $patch . $build );

		if ( $return === false ) {
			$ClientVersion = ClientVersion::firstOrCreate( [
				'major' => $major,
				'minor' => $minor,
				'patch' => $patch,
				'build' => $build
			] );
			$return        = $ClientVersion->id;
			Redis::hset( config( 'cache.prefix' ) . ':clientVersion', $major . $minor . $patch . $build, $return );
		}

		return $return;
	}

	/**
	 * @param string $clientIP
	 *
	 * @return int
	 */
	private function GetClientIpAddressID( string $clientIP ): int {
		$return = Redis::hget( config( 'cache.prefix' ) . ':clientIpAddresses', $clientIP );

		if ( $return === false ) {
			$ClientIpAddress = ClientIpAddress::firstOrCreate( [ 'ip' => $clientIP ] );
			$return          = $ClientIpAddress->id;
			Redis::hset( config( 'cache.prefix' ) . ':clientIpAddresses', $clientIP, $return );
		}

		return $return;

	}

	/**
	 * @param string $clientVersion
	 *
	 * @return array
	 */
	private function ClientVersionParse( string $clientVersion ): array {
		list( $version, $build ) = explode( ' [', $clientVersion );
		$versionExplode = explode( '.', $version );

		if ( count( $versionExplode ) === 2 ) {
			$versionExplode[] = 0;
		} elseif ( count( $versionExplode ) === 1 ) {
			$versionExplode[] = 0;
			$versionExplode[] = 0;
		}

		list( $major, $minor, $patch ) = $versionExplode;

		$build = (int) substr( substr( $build, 0, - 1 ), 7 );

		return [
			'major' => (int) $major,
			'minor' => (int) $minor,
			'patch' => (int) $patch,
			'build' => (int) $build,
		];
	}

	public function tags() {
		return [ 'virtual server clients statistics collections', 'instance:' . $this->instance_id ];
	}

}
