<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoleGroup extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('role_group', function (Blueprint $table) {
            $table->integer('role_id')->unsigned();
            $table->integer('child_id')->unsigned();

            $table->primary(array('role_id', 'child_id'));

            $table->foreign('role_id')->references('role_id')->on('role');
            $table->foreign('child_id')->references('role_id')->on('role');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('role_group');
    }
}
