<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

/**
 * App\StatisticVirtualServer
 *
 * @property int $id
 * @property int $virtual_servers_id
 * @property int $user_online
 * @property int $slot_usage
 * @property float $avg_ping
 * @property float $avg_packetloss
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StatisticVirtualServer dayAvage()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StatisticVirtualServer fiveMinutesAvage()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StatisticVirtualServer halfHourAvage()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StatisticVirtualServer hourAvage()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StatisticVirtualServer minutesAvage()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StatisticVirtualServer popularServerSlotsMonth()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StatisticVirtualServer statDay()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StatisticVirtualServer statMonth()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StatisticVirtualServer statRealtime()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StatisticVirtualServer statWeek()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StatisticVirtualServer statYear()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StatisticVirtualServer virtualServerID($virtual_server_id)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StatisticVirtualServer whereAvgPacketloss($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StatisticVirtualServer whereAvgPing($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StatisticVirtualServer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StatisticVirtualServer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StatisticVirtualServer whereSlotUsage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StatisticVirtualServer whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StatisticVirtualServer whereUserOnline($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\StatisticVirtualServer whereVirtualServersId($value)
 * @mixin \Eloquent
 */
class StatisticVirtualServer extends Model {
	protected $hidden = [ 'id', 'updated_at' ];

	public function scopeVirtualServerID( $query, $virtual_server_id ) {
		return $query->where( 'virtual_servers_id', $virtual_server_id );
	}

	public function scopePopularServerSlotsMonth($query) {
    	return $query->whereBetween( 'created_at', [
		    date( "Y-m-d H:i:s", mktime( 0, 0, 0, date( "m" ) - 1, date( "d" ), date( "Y" ) ) ),
		    date( "Y-m-d H:i:s" )
	    ] )->selectRaw('slot_usage,count(distinct virtual_servers_id) as count')->groupBy('slot_usage');
	}

	public function scopeStatYear( $query ) {
		return $query->whereBetween( 'created_at', [
			date( "Y-m-d H:i:s", mktime( 0, 0, 0, date( "m" ), date( "d" ), date( "Y" ) - 1 ) ),
			date( "Y-m-d H:i:s" )
		] );
	}

	public function scopeStatMonth( $query ) {
		return $query->whereBetween( 'created_at', [
			date( "Y-m-d H:i:s", mktime( 0, 0, 0, date( "m" ) - 1, date( "d" ), date( "Y" ) ) ),
			date( "Y-m-d H:i:s" )
		] );
	}

	function scopeStatWeek( $query ) {
		return $query->whereBetween( 'created_at', [
			date( "Y-m-d H:i:s", time() - 7 * 24 * 60 * 60 ),
			date( "Y-m-d H:i:s" )
		] );
	}

	function scopeStatDay( $query ) {
		return $query->whereBetween( 'created_at', [
			date( "Y-m-d H:i:s", time() - 24 * 60 * 60 ),
			date( "Y-m-d H:i:s" )
		] );
	}

	function scopeStatRealtime( $query ) {
		return $query->whereBetween( 'created_at', [ date( "Y-m-d H:i:s", time() - 60 * 60 ), date( "Y-m-d H:i:s" ) ] );
	}

	function scopeMinutesAvage( $query ) {
		return $query
			->select( DB::raw( 'avg(slot_usage) as slot_usage ,avg(user_online) as user_online,avg(avg_ping) as avg_ping,avg(avg_packetloss) as avg_packetloss,created_at' ) )
			->groupBy( DB::raw( 'floor((unix_timestamp(created_at))/60 ),hour(created_at)' ) );
	}

	function scopeFiveMinutesAvage( $query ) {
		return $query
			->select( DB::raw( 'avg(slot_usage) as slot_usage ,avg(user_online) as user_online,avg(avg_ping) as avg_ping,avg(avg_packetloss) as avg_packetloss,created_at' ) )
			->groupBy( DB::raw( 'floor((unix_timestamp(created_at))/300 ),hour(created_at)' ) );
	}

	function scopeHalfHourAvage( $query ) {
		return $query
			->select( DB::raw( 'avg(slot_usage) as slot_usage ,avg(user_online) as user_online,avg(avg_ping) as avg_ping,avg(avg_packetloss) as avg_packetloss,created_at' ) )
			->groupBy( DB::raw( 'FLOOR((UNIX_TIMESTAMP(created_at) - 1800) / 3600),hour(created_at)' ) );
	}

	public function scopeHourAvage( $query ) {
		return $query
			->select( DB::raw( 'avg(slot_usage) as slot_usage ,avg(user_online) as user_online,avg(avg_ping) as avg_ping,avg(avg_packetloss) as avg_packetloss,created_at' ) )
			->groupBy( DB::raw( 'DAY(created_at),HOUR(created_at)' ) );
	}

	public function scopeDayAvage( $query ) {
		return $query
			->select( DB::raw( 'avg(slot_usage) as slot_usage ,avg(user_online) as user_online,avg(avg_ping) as avg_ping,avg(avg_packetloss) as avg_packetloss,created_at' ) )
			->groupBy( DB::raw( 'MONTH(created_at), DAYOFMONTH(created_at)' ) );
	}

}
