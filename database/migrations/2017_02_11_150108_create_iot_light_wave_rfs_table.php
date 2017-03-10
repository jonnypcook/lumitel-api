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

            $table->unsignedInteger('vendor_id');
            $table->string('_id', 36);
            $table->string('forename', 127)->nullable();
            $table->string('surname', 127)->nullable();
            $table->string('email', 127);
            $table->boolean('active');
            $table->boolean('test');

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
        Schema::dropIfExists('lightwave');
    }
}
