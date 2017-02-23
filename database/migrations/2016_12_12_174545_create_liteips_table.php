<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLiteipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('device_liteip', function (Blueprint $table) {
            $table->increments('device_liteip_id');
            $table->integer('device_liteip_status_id')->unsigned()->nullable();

            $table->unsignedInteger('serial');
            $table->unsignedInteger('vendor_id');
            $table->unsignedInteger('profile_id');
            $table->dateTime('emergency_checked')->nullable();
            $table->boolean('emergency');

            $table->foreign('device_liteip_status_id')->references('device_liteip_status_id')->on('device_liteip_status');

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
        Schema::dropIfExists('device_liteip');
    }
}
