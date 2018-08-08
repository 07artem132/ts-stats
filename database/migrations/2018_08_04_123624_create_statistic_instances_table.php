<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateStatisticInstancesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('statistic_instances', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('instances_id')->unsigned()->index('fk_statistic_instances_instances1_idx');
			$table->smallInteger('slots_usage')->unsigned();
			$table->smallInteger('servers_runing')->unsigned();
			$table->smallInteger('users_online')->unsigned();
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
		Schema::drop('statistic_instances');
	}

}
