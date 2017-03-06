<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIotLiteIpsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('liteip', function (Blueprint $table) {
            $table->increments('liteip_id');
            $table->timestamps();

            $table->unsignedInteger('user_id');
            $table->string('forename', 127)->nullable();
            $table->string('surname', 127)->nullable();
            $table->string('email', 127);
            $table->string('_id', 36);
            $table->boolean('active');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('liteip');
    }
}
