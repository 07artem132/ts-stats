<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToApiLogsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('api_logs', function(Blueprint $table)
		{
			$table->foreign('tokens_id', 'fk_api_logs_tokens1')->references('id')->on('tokens')->onUpdate('NO ACTION')->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('api_logs', function(Blueprint $table)
		{
			$table->dropForeign('fk_api_logs_tokens1');
		});
	}

}
