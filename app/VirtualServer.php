<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\VirtualServer
 *
 * @property int $id
 * @property string $uid
 * @property int $port
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VirtualServer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VirtualServer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VirtualServer wherePort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VirtualServer whereUid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VirtualServer whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class VirtualServer extends Model
{

	protected $fillable = ['uid','port'];

}
