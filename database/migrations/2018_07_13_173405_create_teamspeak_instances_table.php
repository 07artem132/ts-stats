<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTeamspeakInstancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teamspeak_instances', function (Blueprint $table) {
            $table->increments('id');
	        $table->string('name',255);
	        $table->string('ipaddress',255);
	        $table->string('hostname',255);
	        $table->string('username',255);
	        $table->string('password',255);
	        $table->integer('port');
	        $table->tinyInteger('is_enabled');
	        $table->timestamps();

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
        Schema::dropIfExists('teamspeak_instances');
    }
}
