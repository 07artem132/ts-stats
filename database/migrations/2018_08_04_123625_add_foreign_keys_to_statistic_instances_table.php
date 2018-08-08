<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToStatisticInstancesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('statistic_instances', function(Blueprint $table)
		{
			$table->foreign('instances_id', 'fk_statistic_instances_instances1')->references('id')->on('instances')->onUpdate('NO ACTION')->onDelete('NO ACTION');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('statistic_instances', function(Blueprint $table)
		{
			$table->dropForeign('fk_statistic_instances_instances1');
		});
	}

}
