<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\UserInstances
 *
 * @property int $id
 * @property int $users_id
 * @property int $instances_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserInstances whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserInstances whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserInstances whereInstancesId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserInstances whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserInstances whereUsersId($value)
 * @mixin \Eloquent
 */
class UserInstances extends Model
{
    //
}
