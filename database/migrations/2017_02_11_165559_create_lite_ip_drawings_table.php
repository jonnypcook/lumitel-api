<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLiteIpDrawingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('liteip_drawing', function (Blueprint $table) {
            $table->increments('liteip_drawing_id');
            $table->integer('liteip_id')->unsigned();

            $table->string('file', 500);
            $table->integer('width');
            $table->integer('height');
            $table->unsignedInteger('vendor_id');
            $table->timestamps();

            $table->foreign('liteip_id')->references('liteip_id')->on('liteip');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('liteip_drawing');
    }
}
