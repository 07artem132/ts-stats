<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\StatisticVirtualServerClient
 *
 * @property int $id
 * @property int $virtual_servers_id
 * @property int $clients_id
 * @property int $client_contries_id
 * @property int $client_nicknames_id
 * @property int $client_ip_addresses_id
 * @property int $client_platforms_id
 * @property int $client_versions_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StatisticVirtualServerClient whereClientContriesId( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StatisticVirtualServerClient whereClientIpAddressesId( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StatisticVirtualServerClient whereClientNicknamesId( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StatisticVirtualServerClient whereClientPlatformsId( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StatisticVirtualServerClient whereClientVersionsId( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StatisticVirtualServerClient whereClientsId( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StatisticVirtualServerClient whereCreatedAt( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StatisticVirtualServerClient whereId( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StatisticVirtualServerClient whereUpdatedAt( $value )
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StatisticVirtualServerClient whereVirtualServersId( $value )
 * @mixin \Eloquent
 */
class StatisticVirtualServerClient extends Model {

	public function scopeVirtualServersId( $query, $virtualServerId ) {
		return $query->where( 'virtual_servers_id', $virtualServerId );
	}

	public function scopeYear( $query, $InitialSearchDate = null ) {
		if ( empty( $InitialSearchDate ) || strtotime( $InitialSearchDate ) < mktime( 0, 0, 0, date( "m" ), date( "d" ), date( "Y" ) - 1 ) ) {
			return $query->whereBetween( 'created_at', [
				date( "Y-m-d H:i:s", mktime( 0, 0, 0, date( "m" ), date( "d" ), date( "Y" ) - 1 ) ),
				date( "Y-m-d H:i:s" )
			] )->orderBy( 'created_at' );
		} else {
			return $query->whereBetween( 'created_at', [
				date( "Y-m-d H:i:s", strtotime( $InitialSearchDate ) ),
				date( "Y-m-d H:i:s" )
			] )->orderBy( 'created_at' );
		}
	}

	public function scopeMonth( $query, $InitialSearchDate = null ) {
		if ( empty( $InitialSearchDate ) || strtotime( $InitialSearchDate ) < mktime( 0, 0, 0, date( "m" ) - 1, date( "d" ), date( "Y" ) ) ) {
			return $query->whereBetween( 'created_at', [
				date( "Y-m-d H:i:s", mktime( 0, 0, 0, date( "m" ) - 1, date( "d" ), date( "Y" ) ) ),
				date( "Y-m-d H:i:s" )
			] )->orderBy( 'created_at' );
		} else {
			return $query->whereBetween( 'created_at', [
				date( "Y-m-d H:i:s", strtotime( $InitialSearchDate ) ),
				date( "Y-m-d H:i:s" )
			] )->orderBy( 'created_at' );
		}
	}

	function scopeWeek( $query, $InitialSearchDate = null ) {
		if ( empty( $InitialSearchDate ) || strtotime( $InitialSearchDate ) < time() - 7 * 24 * 60 * 60 ) {
			return $query->whereBetween( 'created_at', [
				date( "Y-m-d H:i:s", time() - 7 * 24 * 60 * 60 ),
				date( "Y-m-d H:i:s" )
			] )->orderBy( 'created_at' );
		} else {
			return $query->whereBetween( 'created_at', [
				date( "Y-m-d H:i:s", strtotime( $InitialSearchDate ) ),
				date( "Y-m-d H:i:s" )
			] )->orderBy( 'created_at' );
		}
	}

	function scopeDay( $query, $InitialSearchDate = null ) {
		if ( empty( $InitialSearchDate ) || strtotime( $InitialSearchDate ) < time() - 24 * 60 * 60 ) {
			return $query->whereBetween( 'created_at', [
				date( "Y-m-d H:i:s", time() - 24 * 60 * 60 ),
				date( "Y-m-d H:i:s" )
			] )->orderBy( 'created_at' );
		} else {
			return $query->whereBetween( 'created_at', [
				date( "Y-m-d H:i:s", strtotime( $InitialSearchDate ) ),
				date( "Y-m-d H:i:s" )
			] )->orderBy( 'created_at' );
		}
	}

	function scopeHour( $query, $InitialSearchDate = null ) {
		if ( empty( $InitialSearchDate ) || strtotime( $InitialSearchDate ) < time() - 60 * 60 ) {
			return $query->whereBetween( 'created_at', [
				date( "Y-m-d H:i:s", time() - 60 * 60 ),
				date( "Y-m-d H:i:s" )
			] )->orderBy( 'created_at' );
		} else {
			return $query->whereBetween( 'created_at', [
				date( "Y-m-d H:i:s", strtotime( $InitialSearchDate ) ),
				date( "Y-m-d H:i:s" )
			] )->orderBy( 'created_at' );
		}
	}

	function scopeUniqueClientsCount( $query ) {
		return $query->distinct( 'clients_id' )->count('clients_id');
	}

}
