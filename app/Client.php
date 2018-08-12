<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Client
 *
 * @property int $id
 * @property string $uid
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Client whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Client whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Client whereUid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Client whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Client extends Model
{
	protected $fillable = ['uid'];
}
