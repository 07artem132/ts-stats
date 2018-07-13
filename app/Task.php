<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
