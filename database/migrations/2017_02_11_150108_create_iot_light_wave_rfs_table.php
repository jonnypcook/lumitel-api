<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIotLightWaveRFsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lightwave', function (Blueprint $table) {
            $table->increments('lightwave_id');
            $table->timestamps();

            $table->string('name');
            $table->boolean('active');
            $table->boolean('test');
            $table->unsignedInteger('vendor_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lightwave');
    }
}
