<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateClientVersionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('client_versions', function(Blueprint $table)
		{
			$table->increments('id');
			$table->boolean('major');
			$table->boolean('minor');
			$table->boolean('patch');
			$table->integer('build')->unsigned();
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
		Schema::drop('client_versions');
	}

}
