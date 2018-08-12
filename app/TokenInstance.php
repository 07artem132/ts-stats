<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\TokenInstance
 *
 * @property int $id
 * @property int $tokens_id
 * @property int $instances_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TokenInstance whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TokenInstance whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TokenInstance whereInstancesId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TokenInstance whereTokensId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TokenInstance whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class TokenInstance extends Model
{
    //
}
