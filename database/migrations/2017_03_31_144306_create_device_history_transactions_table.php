<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeviceHistoryTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('device_history_transaction', function (Blueprint $table) {
            $table->increments('device_history_transaction_id');
            $table->integer('device_id')->unsigned();
            $table->integer('telemetry_id')->unsigned();
            $table->timestamp('from')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('to')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('completed_at')->nullable();

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
        Schema::dropIfExists('device_history_transaction');
    }
}
