<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\InstanceVirtualServer
 *
 * @property int $id
 * @property int $instances_id
 * @property int $virtual_servers_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\VirtualServer $virtualServer
 * @method static \Illuminate\Database\Eloquent\Builder|\App\InstanceVirtualServer instanceId($instance_id)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\InstanceVirtualServer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\InstanceVirtualServer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\InstanceVirtualServer whereInstancesId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\InstanceVirtualServer whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\InstanceVirtualServer whereVirtualServersId($value)
 * @mixin \Eloquent
 */
class InstanceVirtualServer extends Model
{
	protected $fillable = ['instances_id','virtual_servers_id'];

	public function scopeInstanceId( $query, $instance_id ) {
		return $query->where( 'instances_id', $instance_id );
	}
	public function virtualServer(){
		return $this->hasMany( 'App\VirtualServer', 'id','virtual_servers_id');
	}
	public function StatisticVirtualServerClients(){
		return $this->hasMany( 'App\StatisticVirtualServerClient', 'virtual_servers_id','virtual_servers_id');
	}
}
