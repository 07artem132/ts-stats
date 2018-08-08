<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateStatisticVirtualServersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('statistic_virtual_servers', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('virtual_servers_id')->unsigned()->index('fk_statistics_virtual_servers_virtual_servers1_idx');
			$table->smallInteger('user_online')->unsigned();
			$table->smallInteger('slot_usage')->unsigned();
			$table->float('avg_ping');
			$table->float('avg_packetloss');
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
		Schema::drop('statistic_virtual_servers');
	}

}
