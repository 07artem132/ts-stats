<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTokenAllowIpAddressTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('token_allow_ip_address', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('tokens_id')->unsigned()->index('fk_token_allow_ip_address_tokens1_idx');
			$table->string('ip', 45);
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
		Schema::drop('token_allow_ip_address');
	}

}
