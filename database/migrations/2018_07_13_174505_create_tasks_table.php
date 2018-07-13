<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->increments('id');
	        $table->smallInteger('priority')->unsigned;
	        $table->string('class_name',255);
	        $table->tinyInteger('is_enabled');
	        $table->tinyInteger('is_periodic');
	        $table->string('frequency',100);
	        $table->string('name',255);
	        $table->string('description',255);
	        $table->timestamps();
	        $table->timestamp('last_run')->nullable();

	        $table->engine = 'InnoDB';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tasks');
    }
}
