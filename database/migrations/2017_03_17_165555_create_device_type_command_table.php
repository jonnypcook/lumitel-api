<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeviceTypeCommandTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('device_type_command', function (Blueprint $table) {
            $table->integer('device_type_id')->unsigned();
            $table->integer('command_id')->unsigned();

            $table->primary(array('device_type_id', 'command_id'), 'device_type_command_pk');

            $table->foreign('device_type_id')->references('device_type_id')->on('device_type');
            $table->foreign('command_id')->references('command_id')->on('command');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('device_type_command');
    }
}
