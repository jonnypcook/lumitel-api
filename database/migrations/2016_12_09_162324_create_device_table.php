<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeviceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('device', function (Blueprint $table) {
            $table->increments('device_id');
            $table->integer('device_type_id')->unsigned();
            $table->integer('space_id')->unsigned();
            $table->boolean('emergency')->default(false);
            $table->integer('serial')->nullable();

            // setup polymorphic fields
            $table->integer('provider_id');
            $table->string('provider_type');

            $table->timestamps();

            $table->foreign('space_id')->references('space_id')->on('space');
            $table->foreign('device_type_id')->references('device_type_id')->on('device_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('device');
    }
}
