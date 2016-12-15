<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSpaceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('space', function (Blueprint $table) {
            $table->increments('space_id');
            $table->string('name', 100);
            $table->string('description')->nullable();
            $table->timestamps();
            $table->integer('floor_id')->unsigned();
            $table->foreign('floor_id')->references('floor_id')->on('floor');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('space');
    }
}
