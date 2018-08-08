<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToUserInstancesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('user_instances', function(Blueprint $table)
		{
			$table->foreign('instances_id', 'fk_user_instances_instances1')->references('id')->on('instances')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('users_id', 'fk_user_instances_users1')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('user_instances', function(Blueprint $table)
		{
			$table->dropForeign('fk_user_instances_instances1');
			$table->dropForeign('fk_user_instances_users1');
		});
	}

}
