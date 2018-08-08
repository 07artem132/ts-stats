<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InstanceVirtualServer extends Model
{
	protected $fillable = ['instances_id','virtual_servers_id'];

	public function scopeInstanceId( $query, $instance_id ) {
		return $query->where( 'instances_id', $instance_id );
	}
	public function virtualServer(){
		return $this->hasOne( 'App\VirtualServer', 'id','virtual_servers_id');

	}
}
