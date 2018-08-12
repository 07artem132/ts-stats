<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Token
 *
 * @property int $id
 * @property int $user_id
 * @property string $token
 * @property int $app_type
 * @property int $blocked
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Token userID($id)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Token whereAppType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Token whereBlocked($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Token whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Token whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Token whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Token whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Token whereUserId($value)
 * @mixin \Eloquent
 */
class Token extends Model {
	function scopeUserID( $query, $id ) {
		return $query->where( 'user_id', '=', $id );
	}
}
