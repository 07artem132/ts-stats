<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToInstanceVirtualServersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('instance_virtual_servers', function(Blueprint $table)
		{
			$table->foreign('instances_id', 'fk_instance_virtual_servers_instances1')->references('id')->on('instances')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('virtual_servers_id', 'fk_instance_virtual_servers_virtual_servers1')->references('id')->on('virtual_servers')->onUpdate('NO ACTION')->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('instance_virtual_servers', function(Blueprint $table)
		{
			$table->dropForeign('fk_instance_virtual_servers_instances1');
			$table->dropForeign('fk_instance_virtual_servers_virtual_servers1');
		});
	}

}
