<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLightwavesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('device_lightwave', function (Blueprint $table) {
            $table->increments('device_lightwave_id');
            $table->unsignedInteger('vendor_id'); // equivalent to device_id

            // lightWave identifiers
            $table->unsignedInteger('room_id')->default(0);
            $table->unsignedInteger('zone_id')->default(0);
            $table->unsignedInteger('user_id')->default(0);
            $table->unsignedInteger('device_id');
            $table->unsignedInteger('device_type_id');
            $table->unsignedInteger('wfl_id')->default(0);
            $table->unsignedInteger('dm_id')->default(0);

            // other
            $table->string('name', 255);
            $table->boolean('active');
            $table->tinyInteger('device_number');
            $table->string('serial', 64);
            $table->integer('rank')->nullable();
            $table->integer('energy_rank')->nullable();
            $table->integer('trigger_rank')->nullable();
            $table->integer('heating_rank')->nullable();
            $table->float('unit_rate', 8, 2)->nullable()->default(0);
            $table->string('device_type_name', 255)->nullable();
            $table->string('wfl_code', 3)->nullable();
            $table->boolean('is_heating')->default(false);
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
        Schema::dropIfExists('device_lightwave');
    }
}
