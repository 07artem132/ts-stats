<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\ClientVersion
 *
 * @property int $id
 * @property int $major
 * @property int $minor
 * @property int $patch
 * @property int $build
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\StatisticVirtualServerClient[] $client
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ClientVersion whereBuild($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ClientVersion whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ClientVersion whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ClientVersion whereMajor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ClientVersion whereMinor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ClientVersion wherePatch($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ClientVersion whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ClientVersion extends Model {
	protected $fillable = [ 'major', 'minor', 'patch', 'build' ];

	function client() {
		return $this->hasMany( 'App\StatisticVirtualServerClient', 'client_versions_id' );
	}

	function clientUseVersionMonth() {
		return $this->hasMany( 'App\StatisticVirtualServerClient', 'client_versions_id' )->distinct( 'clients_id' )->whereBetween( 'created_at', [
			date( "Y-m-d H:i:s", mktime( 0, 0, 0, date( "m" ) - 1, date( "d" ), date( "Y" ) ) ),
			date( "Y-m-d H:i:s" )
		] )->count('clients_id');
	}

}
