<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\ApiLog
 *
 * @property int $id
 * @property string $token
 * @property string $method
 * @property float $execute_time
 * @property string $client_ip
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ApiLog whereClientIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ApiLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ApiLog whereExecuteTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ApiLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ApiLog whereMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ApiLog whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ApiLog whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ApiLog extends Model
{
    //
}
