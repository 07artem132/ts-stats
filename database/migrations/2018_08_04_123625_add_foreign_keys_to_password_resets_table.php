<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToPasswordResetsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('password_resets', function(Blueprint $table)
		{
			$table->foreign('email', 'fk_password_resets_users1')->references('email')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('password_resets', function(Blueprint $table)
		{
			$table->dropForeign('fk_password_resets_users1');
		});
	}

}
