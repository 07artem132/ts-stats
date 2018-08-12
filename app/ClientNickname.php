<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\ClientNickname
 *
 * @property int $id
 * @property string $nickname
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ClientNickname whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ClientNickname whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ClientNickname whereNickname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ClientNickname whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ClientNickname extends Model
{
	protected $fillable = ['nickname'];

}
