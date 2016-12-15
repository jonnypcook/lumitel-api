<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activity', function (Blueprint $table) {
            $table->increments('activity_id');
            $table->integer('activity_type_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->timestamp('created')->default(\Illuminate\Support\Facades\DB::raw('CURRENT_TIMESTAMP'));
            $table->string('description')->nullable();
            $table->string('object_type');
            $table->integer('object_id')->unsigned();

            $table->foreign('activity_type_id')->references('activity_type_id')->on('activity_type');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('activity');
    }
}
