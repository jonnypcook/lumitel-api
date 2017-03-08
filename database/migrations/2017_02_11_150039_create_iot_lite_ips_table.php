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

            $table->unsignedInteger('vendor_id');
            $table->unsignedInteger('customer_id');

            $table->string('postcode', 12);
            $table->string('description', 255)->nullable();
            $table->boolean('active');
            $table->timestamps();
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
