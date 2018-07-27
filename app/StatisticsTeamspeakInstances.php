<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class StatisticsTeamspeakInstances extends Model
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
		return $this->belongsTo( 'Api\TeamspeakInstances', 'id', 'instance_id' );
	}

}
