<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToTokenAllowIpAddressTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('token_allow_ip_address', function(Blueprint $table)
		{
			$table->foreign('tokens_id', 'fk_token_allow_ip_address_tokens1')->references('id')->on('tokens')->onUpdate('NO ACTION')->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('token_allow_ip_address', function(Blueprint $table)
		{
			$table->dropForeign('fk_token_allow_ip_address_tokens1');
		});
	}

}
