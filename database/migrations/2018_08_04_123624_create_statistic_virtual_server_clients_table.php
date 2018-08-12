<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateStatisticVirtualServerClientsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('statistic_virtual_server_clients', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('virtual_servers_id')->unsigned()->index('fk_statistics_virtual_server_clients_virtual_servers1_idx');
			$table->integer('clients_id')->unsigned()->index('fk_statistics_virtual_server_clients_clients1_idx');
			$table->integer('client_contries_id')->unsigned()->index('fk_statistics_virtual_server_clients_client_contries1_idx');
			$table->integer('client_nicknames_id')->unsigned()->index('fk_statistics_virtual_server_clients_client_nicknames1_idx');
			$table->integer('client_ip_addresses_id')->unsigned()->index('fk_statistics_virtual_server_clients_client_ip_addresses_idx');
			$table->integer('client_platforms_id')->unsigned()->index('fk_statistics_virtual_server_clients_client_platforms1_idx');
			$table->integer('client_versions_id')->unsigned()->index('fk_statistics_virtual_server_clients_client_versions1_idx');
			$table->timestamp('created_at', 0)->nullable()->index('idx_statistic_virtual_server_clients_created_at');
			$table->timestamp('updated_at', 0)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('statistic_virtual_server_clients');
	}

}
