<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToTokenInstancesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('token_instances', function(Blueprint $table)
		{
			$table->foreign('instances_id', 'fk_token_instances_instances1')->references('id')->on('instances')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('tokens_id', 'fk_token_instances_tokens1')->references('id')->on('tokens')->onUpdate('NO ACTION')->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('token_instances', function(Blueprint $table)
		{
			$table->dropForeign('fk_token_instances_instances1');
			$table->dropForeign('fk_token_instances_tokens1');
		});
	}

}
