<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Token extends Model {
	function scopeUserID( $query, $id ) {
		return $query->where( 'user_id', '=', $id );
	}
}
