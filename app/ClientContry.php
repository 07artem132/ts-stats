<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\ClientContry
 *
 * @property int $id
 * @property string $country
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\StatisticVirtualServerClient[] $client
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ClientContry whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ClientContry whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ClientContry whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ClientContry whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ClientContry extends Model
{
	protected $fillable = ['country'];

	function client() {
		return $this->hasMany( 'App\StatisticVirtualServerClient', 'client_contries_id' );
	}

	function clientFromTheCountryMonth() {
		return $this->hasMany( 'App\StatisticVirtualServerClient', 'client_contries_id' )->distinct( 'clients_id' )->whereBetween( 'created_at', [
			date( "Y-m-d H:i:s", mktime( 0, 0, 0, date( "m" ) - 1, date( "d" ), date( "Y" ) ) ),
			date( "Y-m-d H:i:s" )
		] )->count('clients_id');
	}

}
