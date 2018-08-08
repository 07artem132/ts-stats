<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToTaskLogsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('task_logs', function(Blueprint $table)
		{
			$table->foreign('tasks_id', 'fk_task_logs_tasks')->references('id')->on('tasks')->onUpdate('NO ACTION')->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('task_logs', function(Blueprint $table)
		{
			$table->dropForeign('fk_task_logs_tasks');
		});
	}

}
