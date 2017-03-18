<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeviceTelemetryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('device_type_telemetry', function (Blueprint $table) {
            $table->integer('device_type_id')->unsigned();
            $table->integer('telemetry_id')->unsigned();

            $table->primary(array('device_type_id', 'telemetry_id'), 'device_type_telemetry_pk');

            $table->foreign('device_type_id')->references('device_type_id')->on('device_type');
            $table->foreign('telemetry_id')->references('telemetry_id')->on('telemetry');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('device_type_telemetry');
    }
}
