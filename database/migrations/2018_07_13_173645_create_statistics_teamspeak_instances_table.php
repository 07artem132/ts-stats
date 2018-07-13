<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStatisticsTeamspeakInstancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('statistics_teamspeak_instances', function (Blueprint $table) {
            $table->increments('id');
	        $table->unsignedInteger('instance_id');
	        $table->smallInteger('slots_usage')->unsigned();
	        $table->smallInteger('servers_runing')->unsigned();
	        $table->smallInteger('users_online')->unsigned();
	        $table->timestamps();
	        $table->engine = 'InnoDB';
	        $table->index( [  'instance_id' ] );
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('statistics_teamspeak_instances');
    }
}
