<?php
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App{
/**
 * App\User
 *
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 */
	class User extends \Eloquent {}
}

namespace App{
/**
 * App\TeamspeakInstances
 *
 * @property-read \App\TeamspeakInstances $instance
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TeamspeakInstances dayAvage()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TeamspeakInstances fiveMinutesAvage()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TeamspeakInstances halfHourAvage()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TeamspeakInstances hourAvage()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TeamspeakInstances instanceId($instance_id)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TeamspeakInstances statDay($InitialSearchDate = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TeamspeakInstances statMonth($InitialSearchDate = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TeamspeakInstances statRealtime($InitialSearchDate = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TeamspeakInstances statWeek($InitialSearchDate = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TeamspeakInstances statYear($InitialSearchDate = null)
 */
	class TeamspeakInstances extends \Eloquent {}
}

namespace App{
/**
 * App\StatisticsTeamspeakInstances
 *
 */
	class StatisticsTeamspeakInstances extends \Eloquent {}
}

namespace App{
/**
 * App\ApiLog
 *
 */
	class ApiLog extends \Eloquent {}
}

namespace App{
/**
 * App\Task
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\TaskLog[] $log
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Task active()
 */
	class Task extends \Eloquent {}
}

namespace App{
/**
 * App\TaskLog
 *
 */
	class TaskLog extends \Eloquent {}
}

