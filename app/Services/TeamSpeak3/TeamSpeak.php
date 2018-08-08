<?php

/**
 * Created by PhpStorm.
 * User: Artem
 * Date: 27.06.2017
 * Time: 17:40
 */

namespace App\Services\TeamSpeak3;

use App\Instance;
use TeamSpeak3;
use TeamSpeak3_Helper_Convert;
use TeamSpeak3_Node_Host;
use TeamSpeak3_Transport_Exception;
use TeamSpeak3_Adapter_ServerQuery_Exception;
use Illuminate\Support\Facades\Log;
use App\Exceptions\TeamSpeakInvalidUidException;
use App\Exceptions\InstanceConfigNotFoundException;

class TeamSpeak {
	protected $connection;
	protected $InstanceConfig;

	/**
	 * TeamSpeak constructor.
	 *
	 * @param int $InstanceID
	 * @param string|null $UID
	 *
	 * @throws InstanceConfigNotFoundException
	 * @throws TeamSpeakInvalidUidException
	 */
	function __construct( int $InstanceID, string $UID = null ) {
		$this->InstanceConfig = $this->GetInstanceConfig( $InstanceID );

		$url = $this->BildUrlConnect();
		Log::debug( 'TeamSpeak connection url: ' . $url );
		$this->Connect( $url );

		if ( $UID != null ) {
			$this->VirtualServerGetByUID( $UID );
			Log::debug( 'TeamSpeak server get by UID: ' . $UID );
		}
	}

	//region Работа с инстансом

	/**
	 * @param array $config
	 */
	function instanceEdit( array $config ): void {
		$this->connection->modify( $config );

		return;
	}

	function bindinglist(): array {
		$data = $this->connection->execute( 'bindinglist' )->toArray()[0];

		foreach ( $data as $key => $value ) {
			$data[ $key ] = (string) $value;
		}

		return $data;
	}

	/**
	 * @return array
	 */
	function serverlist(): array {
		$data = $this->connection->execute( 'serverlist -uid' )->toAssocArray( "virtualserver_id" );

		foreach ( $data as $item ) {
			$data[ $item['virtualserver_id'] ]['virtualserver_status']            = (string) $data[ $item['virtualserver_id'] ]['virtualserver_status'];
			$data[ $item['virtualserver_id'] ]['virtualserver_name']              = (string) $data[ $item['virtualserver_id'] ]['virtualserver_name'];
			$data[ $item['virtualserver_id'] ]['virtualserver_unique_identifier'] = (string) $data[ $item['virtualserver_id'] ]['virtualserver_unique_identifier'];
		}

		return $data;
	}

	/**
	 * @return array
	 */
	function version(): array {
		$data = $this->connection->execute( 'version' )->toArray();

		$data['version']  = (string) $data[0]['version'];
		$data['build']    = (string) $data[0]['build'];
		$data['platform'] = (string) $data[0]['platform'];

		unset( $data[0] );

		return $data;
	}

	/**
	 * @param int $last_pos
	 *
	 * @return array
	 */
	function GetInstanseLog( int $last_pos ): array {
		$logs = $this->connection->logView( 100, $last_pos, null, 1 );

		$data['last_pos']  = $logs[0]['last_pos'];
		$data['file_size'] = $logs[0]['file_size'];

		unset( $logs[0]['last_pos'] );
		unset( $logs[0]['file_size'] );

		for ( $i = 0; $i < count( $logs ); $i ++ ) {
			$log = TeamSpeak3_Helper_Convert::logEntry( $logs[ $i ]["l"] );

			$log['msg']       = (string) $log['msg'];
			$log['msg_plain'] = (string) $log['msg_plain'];

			$data['log'][ $i ] = $log;
		}

		return $data;
	}

	/**
	 * @return array
	 */
	function hostinfo(): array {
		return $this->connection->execute( 'hostinfo' )->toArray();
	}

	/**
	 * @return array
	 */
	function instanceinfo(): array {
		return $this->connection->execute( 'instanceinfo' )->toArray();
	}

	function InstanceStop(): void {
		$this->connection->serverStopProcess();

		return;
	}
	//endregion

	//region Работа с виртуальным сервером
	/**
	 * @param string $uid
	 *
	 * @throws TeamSpeakInvalidUidException
	 */
	function VirtualServerGetByUID( string $uid ): void {
		try {
			$this->connection = $this->connection->serverGetByUid( $uid );
		} catch ( \TeamSpeak3_Adapter_ServerQuery_Exception $e ) {
			throw new TeamSpeakInvalidUidException();
		}
	}

	/**
	 * @return string
	 */
	function GetVirtualServerID(): string {
		return (string) $this->connection['virtualserver_id'];
	}

	/**
	 * @param int $last_pos
	 *
	 * @return array
	 */
	function GetVirtualServerLog( int $last_pos ): array {
		$logs = $this->connection->logView( 100, $last_pos, null, false );

		$data['last_pos']  = $logs[0]['last_pos'];
		$data['file_size'] = $logs[0]['file_size'];

		unset( $logs[0]['last_pos'] );
		unset( $logs[0]['file_size'] );

		for ( $i = 0; $i < count( $logs ); $i ++ ) {
			$log = TeamSpeak3_Helper_Convert::logEntry( $logs[ $i ]["l"] );

			$log['msg']       = (string) $log['msg'];
			$log['msg_plain'] = (string) $log['msg_plain'];

			$data['log'][ $i ] = $log;
		}

		return $data;
	}

	/**
	 * @return array
	 */
	function VirtualServerInfo(): array {
		$data = (array) $this->connection->execute( 'serverinfo' )->toArray();

		foreach ( $data[0] as $key => $value ) {
			$ReturnData[ $key ] = (string) $value;
		}

		return $ReturnData;
	}

	function VirtualServerStop(): void {
		$this->connection->serverStop( $this->connection['virtualserver_id'] );

		return;
	}

	function VirtualServerStart(): void {
		$this->connection->serverStart( $this->connection['virtualserver_id'] );

		return;
	}

	//region Снапшоты

	/**
	 * @return string
	 */
	function VirtualServerSnapshotCreate(): string {
		return (string) $this->connection->snapshotCreate();
	}

	function VirtualServerSnapshotRestore( $Snapshot ): void {
		$this->connection->snapshotDeploy( $Snapshot );

		return;
	}
	//endregion

	//region Токены
	function VirtualServerTokenlList(): array {
		try {
			$data = $this->connection->privilegeKeyList( false );
		} catch ( TeamSpeak3_Adapter_ServerQuery_Exception $e ) {
			if ( $e->getMessage() === 'database empty result set' ) {
				return [ null ];
			}
		}

		foreach ( $data as $Token => $param ) {
			foreach ( $param as $key => $value ) {
				$ReturnData[ $Token ][ $key ] = (string) $value;
			}
		}

		return $ReturnData;
	}

	function VirtualServerTokenDelete( string $Token ): void {
		$this->connection->privilegeKeyDelete( $Token );

		return;
	}

	function VirtualServerTokenCreate( $groupID, $description ): string {
		return (string) $this->connection->privilegeKeyCreate( TeamSpeak3::TOKEN_SERVERGROUP, $groupID, 0, $description );
	}
	//endregion

	/**
	 * @param array $config
	 *
	 * @return array
	 */
	function VirtualServerCreate( array $config ): array {
		$data          = $this->connection->serverCreate( $config );
		$data['token'] = (string) $data['token'];

		return $data;
	}

	function VirtualServerDelete(): void {
		$this->connection->serverDelete( $this->GetVirtualServerID() );

		return;
	}
	//region Баны

	/**
	 * @param int $banid
	 */
	function VirtualServerBanDelete( int $banid ): void {
		$this->connection->banDelete( $banid );

		return;
	}

	function VirtualServerBanListClear(): void {
		$this->connection->banListClear();

		return;
	}

	/**
	 * @param array $rules
	 * @param int $timeseconds
	 * @param string $reason
	 *
	 * @return int
	 */
	function VirtualServerBanCreate( array $rules, int $timeseconds, string $reason ): int {
		return (int) $this->connection->banCreate( $rules, $timeseconds, $reason );
	}

	/**
	 * @return array
	 */
	function VirtualServerBanList(): array {
		try {
			$data = $this->connection->banList();
		} catch ( TeamSpeak3_Adapter_ServerQuery_Exception $e ) {
			if ( $e->getMessage() === 'database empty result set' ) {
				return [ null ];
			}
		}

		foreach ( $data as $banID => $Param ) {
			foreach ( $Param as $key => $value ) {
				$ReturnData[ $banID ][ $key ] = (string) $value;
			}
		}

		return $ReturnData;
	}

	//endregion

	//region Каналы
	/**
	 * @return array
	 */
	function VirtualServerChannelList(): array {
		$Result = (array) $this->connection->execute( 'channellist -topic -flags -voice -limits -icon' )->toAssocArray( "cid" );

		foreach ( $Result as $key => $value ) {
			$Return[ $key ]                  = (array) $value;
			$Return[ $key ]['channel_topic'] = (string) $Return[ $key ]['channel_topic'];
			$Return[ $key ]['channel_name']  = (string) $Return[ $key ]['channel_name'];
		}

		return $Return;
	}

	/**
	 * @param $channelid
	 *
	 * @return array
	 */
	function VirtualServerChannelInfo( $channelid ): array {
		$Result = (array) $this->connection->execute( 'channelinfo cid=' . $channelid )->toArray();

		foreach ( $Result[0] as $key => $value ) {
			$Return[ $key ] = (string) $value;
		}

		return $Return;
	}

	/**
	 * @param $channelid
	 */
	function VirtualServerChannelDelete( $channelid ): void {
		$this->connection->execute( 'channeldelete cid=' . $channelid . ' force=1' );

		return;
	}

	function VirtualServerChannelCreate( $properties ): int {
		$result = (int) $this->connection->channelCreate( $properties );

		return $result;
	}

	function VirtualServerChannelMove( $channelid, $properties ): void {
		$this->connection->channelMove( $channelid, $properties['channel_parent_id'], $properties['channel_sort_order'] );

		return;
	}

	function VirtualServerChannelEdit( $channelid, $properties ): void {
		$this->connection = $this->connection->channelGetById( $channelid );

		foreach ( $properties as $key => $value ) {
			$this->connection[ $key ] = $value;
		}

		return;
	}
	//endregion

	//endregion

	//region Передача файлов
	/**
	 * @param int $cid
	 * @param string $name
	 * @param string $cpw
	 *
	 * @return string
	 */
	function DownloadFile( int $cid, string $name, string $cpw = '' ): string {
		$download = $this->connection->transferInitDownload( rand( 0x0000, 0xFFFF ), $cid, $name, $cpw );
		$transfer = TeamSpeak3::factory( "filetransfer://" . ( strstr( $download["host"], ":" ) !== false ? "[" . $download["host"] . "]" : $download["host"] ) . ":" . $download["port"] );;

		return $transfer->download( $download["ftkey"], $download["size"] );
	}

	/**
	 * @return array
	 */
	function GetVirtualServerIconList(): ?array {
		try {
			$data = $this->connection->channelFileList( '0', '', '/icons' );
		} catch ( TeamSpeak3_Adapter_ServerQuery_Exception  $e ) {
			if ( $e->getMessage() === 'database empty result set' ) {
				return null;
			}
		}

		for ( $i = 0; $i < count( $data ); $i ++ ) {
			$data[ $i ]['path'] = (string) $data[ $i ]['path'];
			$data[ $i ]['name'] = (string) $data[ $i ]['name'];
			$data[ $i ]['src']  = (string) $data[ $i ]['src'];
		}

		return $data;
	}

	function VirtualServerChannelFileList( $cid ): ?array {
		try {
			$data = $this->connection->channelFileList( $cid, '', '/', false );
			do {
				$flag = false;
				for ( $i = 0; $i < count( $data ); $i ++ ) {
					if ( $data[ $i ]["type"] == TeamSpeak3::FILE_TYPE_DIRECTORY ) {
						$flag = true;
						$path = $data[ $i ]["src"];
						unset( $data[ $i ] );
						try {
							$data = array_merge( $data, $this->connection->channelFileList( $cid, '', $path, false ) );
						} catch ( TeamSpeak3_Adapter_ServerQuery_Exception  $e ) {
							if ( $e->getMessage() === 'database empty result set' ) {
								$data = array_merge( $data, [] );
							}
						}
					}
				}
			} while ( $flag != false );
		} catch ( TeamSpeak3_Adapter_ServerQuery_Exception  $e ) {
			if ( $e->getMessage() === 'database empty result set' ) {
				return null;
			}
		}

		for ( $i = 0; $i < count( $data ); $i ++ ) {
			$data[ $i ]['path'] = (string) $data[ $i ]['path'];
			$data[ $i ]['name'] = (string) $data[ $i ]['name'];
			$data[ $i ]['src']  = (string) $data[ $i ]['src'];
		}

		return $data;
	}

	/**
	 * @param string $icon
	 *
	 * @return int
	 */
	function iconUpload( string $icon ): int {
		$crc  = crc32( $icon );
		$size = strlen( $icon );

		$upload   = $this->connection->transferInitUpload( rand( 0x0000, 0xFFFF ), 0, "/icon_" . $crc, $size );
		$transfer = TeamSpeak3::factory( "filetransfer://" . ( strstr( $upload["host"], ":" ) !== false ? "[" . $upload["host"] . "]" : $upload["host"] ) . ":" . $upload["port"] );

		$transfer->upload( $upload["ftkey"], $upload["seekpos"], $icon );

		return $crc;
	}

	/**
	 * @param int $cid
	 * @param string $name
	 * @param string $cpw
	 */
	function DeleteFile( int $cid, string $name, string $cpw = '' ): void {
		$this->connection->channelFileDelete( $cid, $cpw, $name );

		return;
	}

//endregion

	/**
	 * @return string
	 */
	function BildUrlConnect(): string {
		$Options = 'timeout=' . config( 'TeamSpeak.connection.timeout',2 );
		$Options .= '&blocking=' . config( 'TeamSpeak.connection.blocking',1 );
		$Options .= '&nickname=' . config( 'TeamSpeak.connection.nickname','ts-stats'.rand(1,9) );
		$url     = "serverquery://{$this->InstanceConfig->username}:".rawurlencode($this->InstanceConfig->password)."@{$this->InstanceConfig->ipaddress}:{$this->InstanceConfig->port}/$Options";
		$url     .= '#' . config( 'TeamSpeak.connection.flags' );

		return $url;
	}

	function Connect( $url ): void {
		$this->connection = TeamSpeak3::factory( $url );

		return;
	}

	function Reconect(): void {
		$this->connection->__wakeup();

		return;
	}

	function logout(): void {
		try {
			$this->connection->logout();
		} catch ( TeamSpeak3_Adapter_ServerQuery_Exception $e ) {
			//TODO new event TeamSpeak error
			echo $e->getMessage() . PHP_EOL;
		} catch ( TeamSpeak3_Transport_Exception $e ) {
			//TODO new event TeamSpeak error
			echo $e->getMessage() . PHP_EOL;
		}

		return;
	}

	/**
	 * @return TeamSpeak3_Adapter_Abstract
	 */
	function ReturnConnection(): TeamSpeak3_Node_Host {
		return $this->connection;
	}

	/**
	 * @param int $InstanceID Уникальный ID (порядковый номер) инстанса в API
	 *
	 * @return mixed
	 * @throws InstanceConfigNotFoundException
	 */
	function GetInstanceConfig( int $InstanceID ): Instance {
		$config = Instance::where( 'id', '=', $InstanceID )->first();
		if ( empty( $config ) ) {
			throw new InstanceConfigNotFoundException( $InstanceID );
		}

		return $config;
	}
}