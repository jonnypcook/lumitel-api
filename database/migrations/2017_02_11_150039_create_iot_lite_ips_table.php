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

            $table->string('name');
            $table->string('postcode');
            $table->unsignedInteger('customer_group');
            $table->boolean('active');
            $table->boolean('test');
            $table->unsignedInteger('vendor_id');
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
