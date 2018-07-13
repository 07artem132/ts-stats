<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTaskLogsTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create( 'task_logs', function ( Blueprint $table ) {
			$table->increments( 'id' );
			$table->unsignedInteger( 'task_id' );
			$table->tinyInteger( 'status' );
			$table->text( 'message' );
			$table->double( 'run_time', 22, 18 )->nullable();
			$table->timestamps();

			$table->engine = 'InnoDB';
		} );

		DB::statement('ALTER TABLE task_logs  KEY_BLOCK_SIZE = 1 , ROW_FORMAT = COMPRESSED');

	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists( 'task_logs' );
	}
}
