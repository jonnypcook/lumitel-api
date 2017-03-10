<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserInstallationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_installation', function (Blueprint $table) {
            $table->integer('user_id')->unsigned();
            $table->integer('installation_id')->unsigned();

            $table->primary(array('user_id', 'installation_id'));

            $table->foreign('user_id')->references('id')->on('users');
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
        Schema::dropIfExists('user_installation');
    }
}
