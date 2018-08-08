<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateApiLogsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('api_logs', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('tokens_id')->unsigned()->index('fk_api_logs_tokens1_idx');
			$table->string('method');
			$table->float('execute_time', 20, 18)->unsigned();
			$table->string('client_ip');
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
		Schema::drop('api_logs');
	}

}
