<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\UserAdmin
 *
 * @property int $id
 * @property int $users_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserAdmin whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserAdmin whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserAdmin whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserAdmin whereUsersId($value)
 * @mixin \Eloquent
 */
class UserAdmin extends Model
{
    //
}
