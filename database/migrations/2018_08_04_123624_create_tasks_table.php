<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTasksTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('tasks', function(Blueprint $table)
		{
			$table->increments('id');
			$table->smallInteger('priority')->unsigned();
			$table->string('class_name')->unique();
			$table->boolean('is_enabled');
			$table->boolean('is_periodic');
			$table->string('frequency', 100);
			$table->string('name');
			$table->string('description');
			$table->timestamps();
			$table->dateTime('last_run')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('tasks');
	}

}
