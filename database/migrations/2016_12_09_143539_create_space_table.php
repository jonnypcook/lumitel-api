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
            $table->integer('installation_id')->unsigned();
            $table->integer('parent_id')->unsigned()->nullable();
            $table->integer('image_id')->unsigned()->nullable();

            $table->string('name', 100);
            $table->string('description')->nullable();
            $table->timestamps();
            $table->smallInteger('level');
            $table->double('width')->unsigned();
            $table->double('height')->unsigned();
            $table->double('left')->unsigned()->nullable();
            $table->double('top')->unsigned()->nullable();

            $table->foreign('installation_id')->references('installation_id')->on('installation');
            $table->foreign('image_id')->references('image_id')->on('image');
            $table->foreign('parent_id')->references('space_id')->on('space');
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
