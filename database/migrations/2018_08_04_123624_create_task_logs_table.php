<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTaskLogsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('task_logs', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('tasks_id')->unsigned()->index('fk_task_logs_tasks_idx');
			$table->boolean('status');
			$table->text('message', 65535);
			$table->float('run_time', 22, 18)->unsigned();
			$table->timestamps();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('task_logs');
	}

}
