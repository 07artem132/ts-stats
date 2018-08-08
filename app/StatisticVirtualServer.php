<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class StatisticVirtualServer extends Model
{
    protected $hidden = ['id', 'updated_at', 'server_id', 'unique_id'];

    public function scopeInstanceId($query, $instance_id)
    {
        return $query->where('instance_id', $instance_id);
    }

    public function scopeVirtualServerUID($query, $unique_id)
    {
        return $query->where('unique_id', $unique_id);
    }

    public function scopeStatYear($query)
    {
        return $query->whereBetween('created_at', [date("Y-m-d H:i:s", mktime(0, 0, 0, date("m"), date("d"), date("Y") - 1)), date("Y-m-d H:i:s")]);
    }

    public function scopeStatMonth($query)
    {
        return $query->whereBetween('created_at', [date("Y-m-d H:i:s", mktime(0, 0, 0, date("m") - 1, date("d"), date("Y"))), date("Y-m-d H:i:s")]);
    }

    function scopeStatWeek($query)
    {
        return $query->whereBetween('created_at', [date("Y-m-d H:i:s", time() - 7 * 24 * 60 * 60), date("Y-m-d H:i:s")]);
    }

    function scopeStatDay($query)
    {
        return $query->whereBetween('created_at', [date("Y-m-d H:i:s", time() - 24 * 60 * 60), date("Y-m-d H:i:s")]);
    }

    function scopeStatRealtime($query)
    {
        return $query->whereBetween('created_at', [date("Y-m-d H:i:s", time() - 60 * 60), date("Y-m-d H:i:s")]);
    }

    function scopeFiveMinutesAvage($query)
    {
        return $query
            ->select(DB::raw('avg(slot_usage) as slot_usage ,avg(user_online) as user_online,avg(avg_ping) as avg_ping,avg(avg_packetloss) as avg_packetloss,created_at'))
            ->groupBy(DB::raw('floor((unix_timestamp(created_at))/300 ),hour(created_at)'));
    }

    function scopeHalfHourAvage($query)
    {
        return $query
            ->select(DB::raw('avg(slot_usage) as slot_usage ,avg(user_online) as user_online,avg(avg_ping) as avg_ping,avg(avg_packetloss) as avg_packetloss,created_at'))
            ->groupBy(DB::raw('FLOOR((UNIX_TIMESTAMP(created_at) - 1800) / 3600),hour(created_at)'));
    }

    public function scopeHourAvage($query)
    {
        return $query
            ->select(DB::raw('avg(slot_usage) as slot_usage ,avg(user_online) as user_online,avg(avg_ping) as avg_ping,avg(avg_packetloss) as avg_packetloss,created_at'))
            ->groupBy(DB::raw('DAY(created_at),HOUR(created_at)'));
    }

    public function scopeDayAvage($query)
    {
        return $query
            ->select(DB::raw('avg(slot_usage) as slot_usage ,avg(user_online) as user_online,avg(avg_ping) as avg_ping,avg(avg_packetloss) as avg_packetloss,created_at'))
            ->groupBy(DB::raw('MONTH(created_at), DAYOFMONTH(created_at)'));
    }

    public function instance()
    {
        return $this->belongsTo( 'App\Instance', 'id','instance_id');
    }

}
