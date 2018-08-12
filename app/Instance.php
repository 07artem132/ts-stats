<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Instance
 *
 * @property int $id
 * @property string $name
 * @property string $ipaddress
 * @property string $hostname
 * @property string $username
 * @property string $password
 * @property int $port
 * @property int $is_enabled
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Instance $instance
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Instance active()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Instance dayAvage()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Instance fiveMinutesAvage()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Instance halfHourAvage()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Instance hourAvage()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Instance instanceId($instance_id)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Instance statDay($InitialSearchDate = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Instance statMonth($InitialSearchDate = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Instance statRealtime($InitialSearchDate = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Instance statWeek($InitialSearchDate = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Instance statYear($InitialSearchDate = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Instance whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Instance whereHostname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Instance whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Instance whereIpaddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Instance whereIsEnabled($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Instance whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Instance wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Instance wherePort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Instance whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Instance whereUsername($value)
 * @mixin \Eloquent
 */
class Instance extends Model
{

	protected $hidden = [ 'id', 'updated_at', 'instance_id' ];

	public function scopeActive($query)
	{
		return $query->where('is_enabled', 1);
	}

	public function scopeInstanceId( $query, $instance_id ) {
		return $query->where( 'instance_id', $instance_id );
	}

	public function scopeStatYear( $query, $InitialSearchDate = null ) {
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

	public function scopeStatMonth( $query, $InitialSearchDate = null ) {
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

	function scopeStatWeek( $query, $InitialSearchDate = null ) {
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

	function scopeStatDay( $query, $InitialSearchDate = null ) {
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

	function scopeStatRealtime( $query, $InitialSearchDate = null ) {
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

	function scopeFiveMinutesAvage( $query ) {
		return $query
			->select( DB::raw( 'avg(slot_usage) as slot_usage ,avg(server_runing) as server_runing,avg(user_online) as user_online,created_at' ) )
			->groupBy( DB::raw( 'floor((unix_timestamp(created_at))/300 ),hour(created_at)' ) );
	}

	function scopeHalfHourAvage( $query ) {
		return $query
			->select( DB::raw( 'avg(slot_usage) as slot_usage ,avg(server_runing) as server_runing,avg(user_online) as user_online,created_at' ) )
			->groupBy( DB::raw( 'FLOOR((UNIX_TIMESTAMP(created_at) - 1800) / 3600),hour(created_at)' ) );
	}

	public function scopeHourAvage( $query ) {
		return $query
			->select( DB::raw( 'avg(slot_usage) as slot_usage ,avg(server_runing) as server_runing,avg(user_online) as user_online,created_at' ) )
			->groupBy( DB::raw( 'DAY(created_at),HOUR(created_at)' ) );
	}

	public function scopeDayAvage( $query ) {
		return $query
			->select( DB::raw( 'avg(slot_usage) as slot_usage ,avg(server_runing) as server_runing,avg(user_online) as user_online,created_at' ) )
			->groupBy( DB::raw( 'MONTH(created_at), DAYOFMONTH(created_at)' ) );
	}

	public function InstanceVirtualServers() {
		return $this->hasMany( 'App\InstanceVirtualServer', 'instances_id', 'id' );
	}

}
