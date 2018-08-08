<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToStatisticVirtualServerClientsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('statistic_virtual_server_clients', function(Blueprint $table)
		{
			$table->foreign('client_contries_id', 'fk_statistics_virtual_server_clients_client_contries1')->references('id')->on('client_contries')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('client_ip_addresses_id', 'fk_statistics_virtual_server_clients_client_ip_addresses')->references('id')->on('client_ip_addresses')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('client_nicknames_id', 'fk_statistics_virtual_server_clients_client_nicknames1')->references('id')->on('client_nicknames')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('client_platforms_id', 'fk_statistics_virtual_server_clients_client_platforms1')->references('id')->on('client_platforms')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('client_versions_id', 'fk_statistics_virtual_server_clients_client_versions1')->references('id')->on('client_versions')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('clients_id', 'fk_statistics_virtual_server_clients_clients1')->references('id')->on('clients')->onUpdate('NO ACTION')->onDelete('NO ACTION');
			$table->foreign('virtual_servers_id', 'fk_statistics_virtual_server_clients_virtual_servers1')->references('id')->on('virtual_servers')->onUpdate('NO ACTION')->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('statistic_virtual_server_clients', function(Blueprint $table)
		{
			$table->dropForeign('fk_statistics_virtual_server_clients_client_contries1');
			$table->dropForeign('fk_statistics_virtual_server_clients_client_ip_addresses1');
			$table->dropForeign('fk_statistics_virtual_server_clients_client_nicknames1');
			$table->dropForeign('fk_statistics_virtual_server_clients_client_platforms1');
			$table->dropForeign('fk_statistics_virtual_server_clients_client_versions1');
			$table->dropForeign('fk_statistics_virtual_server_clients_clients1');
			$table->dropForeign('fk_statistics_virtual_server_clients_virtual_servers1');
		});
	}

}
