<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToStatisticVirtualServersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('statistic_virtual_servers', function(Blueprint $table)
		{
			$table->foreign('virtual_servers_id', 'fk_statistics_virtual_servers_virtual_servers1')->references('id')->on('virtual_servers')->onUpdate('NO ACTION')->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('statistic_virtual_servers', function(Blueprint $table)
		{
			$table->dropForeign('fk_statistics_virtual_servers_virtual_servers1');
		});
	}

}
