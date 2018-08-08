<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToUserAdminsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('user_admins', function(Blueprint $table)
		{
			$table->foreign('users_id', 'fk_user_admins_users1')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('user_admins', function(Blueprint $table)
		{
			$table->dropForeign('fk_user_admins_users1');
		});
	}

}
