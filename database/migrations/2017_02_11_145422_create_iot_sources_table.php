<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIotSourcesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('iot_source', function (Blueprint $table) {
            $table->increments('iot_source_id');
            $table->integer('installation_id')->unsigned();

            // setup polymorphic fields
            $table->integer('provider_id');
            $table->string('provider_type');

            $table->timestamps();

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
        Schema::dropIfExists('iot_source');
    }
}
