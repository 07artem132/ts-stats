<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\TaskLog
 *
 * @property int $id
 * @property int $task_id
 * @property int $status
 * @property string $message
 * @property float|null $run_time
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TaskLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TaskLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TaskLog whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TaskLog whereRunTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TaskLog whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TaskLog whereTaskId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TaskLog whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property int $tasks_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TaskLog whereTasksId($value)
 */
class TaskLog extends Model
{
    //
}
