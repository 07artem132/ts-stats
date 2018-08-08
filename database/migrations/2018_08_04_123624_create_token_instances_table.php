<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTokenInstancesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('token_instances', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('tokens_id')->unsigned()->index('fk_token_instances_tokens1_idx');
			$table->integer('instances_id')->unsigned()->index('fk_token_instances_instances1_idx');
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
		Schema::drop('token_instances');
	}

}
