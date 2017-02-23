<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInstallationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('installation', function (Blueprint $table) {
            $table->increments('installation_id');
            $table->integer('owner_id')->unsigned();
            $table->integer('address_id')->unsigned();
            $table->string('name', 100);
            $table->dateTime('commissioned');
            $table->timestamps();

            // setup keys
            $table->foreign('owner_id')->references('owner_id')->on('owner');
            $table->foreign('address_id')->references('address_id')->on('address');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('installation');
    }
}
