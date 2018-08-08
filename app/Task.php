<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Task
 *
 * @property int $id
 * @property int $priority
 * @property string $class_name
 * @property int $is_enabled
 * @property int $is_periodic
 * @property string $frequency
 * @property string $name
 * @property string $description
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property string|null $last_run
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\TaskLog[] $log
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Task active()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Task whereClassName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Task whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Task whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Task whereFrequency($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Task whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Task whereIsEnabled($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Task whereIsPeriodic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Task whereLastRun($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Task whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Task wherePriority($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Task whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Task extends Model
{
	/**
	 * Заготовка запроса для получения активных задач.
	 * @param $query
	 * @return mixed
	 */
	public function scopeActive($query)
	{
		return $query->where('is_enabled', 1);
	}

	function log()
	{
		return $this->hasMany('App\TaskLog', 'task_id', 'id');
	}

}
