<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\ClientPlatform
 *
 * @property int $id
 * @property string $platform
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ClientPlatform whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ClientPlatform whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ClientPlatform wherePlatform($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ClientPlatform whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ClientPlatform extends Model
{
	protected $fillable = ['platform'];

	function clientUsePlatformMonth(){
		return $this->hasMany( 'App\StatisticVirtualServerClient', 'client_platforms_id' )->distinct( 'clients_id' )->whereBetween( 'created_at', [
			date( "Y-m-d H:i:s", mktime( 0, 0, 0, date( "m" ) - 1, date( "d" ), date( "Y" ) ) ),
			date( "Y-m-d H:i:s" )
		] )->count('clients_id');
	}
}
