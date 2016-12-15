<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFloorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('floor', function (Blueprint $table) {
            $table->increments('floor_id');
            $table->integer('installation_id')->unsigned();
            $table->string('name');
            $table->integer('vendor_id')->unsigned()->nullable();
            $table->smallInteger('level');
            $table->boolean('image');
            $table->timestamps();

            // setup keys
            $table->foreign('installation_id')->references('installation_id')->on('installation');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('floor');
    }
}
