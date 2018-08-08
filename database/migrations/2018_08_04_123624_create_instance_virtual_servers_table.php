<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateInstanceVirtualServersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('instance_virtual_servers', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('instances_id')->unsigned()->index('fk_instance_virtual_servers_instances1_idx');
			$table->integer('virtual_servers_id')->unsigned()->index('fk_instance_virtual_servers_virtual_servers1_idx');
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
		Schema::drop('instance_virtual_servers');
	}

}
