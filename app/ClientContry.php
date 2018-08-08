<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
