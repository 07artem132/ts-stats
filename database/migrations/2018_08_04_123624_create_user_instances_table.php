<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUserInstancesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('user_instances', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('users_id')->unsigned()->index('fk_user_instances_users1_idx');
			$table->integer('instances_id')->unsigned()->index('fk_user_instances_instances1_idx');
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
		Schema::drop('user_instances');
	}

}
