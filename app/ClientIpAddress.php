<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\ClientIpAddress
 *
 * @property int $id
 * @property string $ip
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ClientIpAddress whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ClientIpAddress whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ClientIpAddress whereIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ClientIpAddress whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ClientIpAddress extends Model
{
	protected $fillable = ['ip'];

}
