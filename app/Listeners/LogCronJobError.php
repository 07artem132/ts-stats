<?php

namespace App\Listeners;

use App\Task;
use App\TaskLog;
use App\Events\CronJobError;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class LogCronJobError
{

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  CronJobError $event
     * @return void
     */
    public function handle(CronJobError $event)
    {
        $task_logs = new TaskLog;
        $task_logs->tasks_id = $event->tasks_id;
        $task_logs->status = 0;
        $task_logs->message = $event->return;
        $task_logs->run_time = $event->runtime;
        $task_logs->save();

        $Task = task::find($event->tasks_id);

        $Task->last_run = date('Y-m-d H:i:s', $event->rundate);

        $Task->save();

    }
}
