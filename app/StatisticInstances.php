<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

/**
 * App\StatisticInstances
 *
 * @property int $id
 * @property int $instance_id
 * @property int $slots_usage
 * @property int $servers_runing
 * @property int $users_online
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Instance $instance
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StatisticInstances dayAvage()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StatisticInstances fiveMinutesAvage()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StatisticInstances halfHourAvage()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StatisticInstances hourAvage()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StatisticInstances instanceId($instance_id)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StatisticInstances statDay($InitialSearchDate = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StatisticInstances statMonth($InitialSearchDate = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StatisticInstances statRealtime($InitialSearchDate = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StatisticInstances statWeek($InitialSearchDate = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StatisticInstances statYear($InitialSearchDate = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StatisticInstances whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StatisticInstances whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StatisticInstances whereInstanceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StatisticInstances whereServersRuning($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StatisticInstances whereSlotsUsage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StatisticInstances whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StatisticInstances whereUsersOnline($value)
 * @mixin \Eloquent
 */
class StatisticInstances extends Model
{

	protected $hidden = [ 'id', 'updated_at', 'instance_id' ];

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
			->select( DB::raw( 'avg(slots_usage) as slots_usage ,avg(servers_runing) as servers_runing,avg(users_online) as users_online,created_at' ) )
			->groupBy( DB::raw( 'floor((unix_timestamp(created_at))/300 ),hour(created_at)' ) );
	}

	function scopeHalfHourAvage( $query ) {
		return $query
			->select( DB::raw( 'avg(slots_usage) as slots_usage ,avg(servers_runing) as servers_runing,avg(users_online) as users_online,created_at' ) )
			->groupBy( DB::raw( 'FLOOR((UNIX_TIMESTAMP(created_at) - 1800) / 3600),hour(created_at)' ) );
	}

	public function scopeHourAvage( $query ) {
		return $query
			->select( DB::raw( 'avg(slots_usage) as slots_usage ,avg(servers_runing) as servers_runing,avg(users_online) as users_online,created_at' ) )
			->groupBy( DB::raw( 'DAY(created_at),HOUR(created_at)' ) );
	}

	public function scopeDayAvage( $query ) {
		return $query
			->select( DB::raw( 'avg(slots_usage) as slots_usage ,avg(servers_runing) as servers_runing,avg(users_online) as users_online,created_at' ) )
			->groupBy( DB::raw( 'MONTH(created_at), DAYOFMONTH(created_at)' ) );
	}

	public function instance() {
		return $this->belongsTo( 'App\Instance', 'id', 'instance_id' );
	}

}
