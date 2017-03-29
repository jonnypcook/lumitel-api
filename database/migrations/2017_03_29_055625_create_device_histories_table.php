<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeviceHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('device_history', function (Blueprint $table) {
            $table->increments('device_history_id');
            $table->integer('device_id')->unsigned();
            $table->integer('telemetry_id')->unsigned();
            $table->float('reading_current', 8, 2);
            $table->float('reading_day', 8, 2);
            $table->timestamp('utc_created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('local_created_at')->default(DB::raw('CURRENT_TIMESTAMP'));

            $table->foreign('device_id')->references('device_id')->on('device');
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
        Schema::dropIfExists('device_history');
    }
}
