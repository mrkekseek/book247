<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShopLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shop_locations', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->string('name');
            $table->integer('address_id')->unsigned();
            $table->string('bank_acc_no');
            $table->string('phone');
            $table->string('fax');
            $table->string('email');
            $table->string('registered_no');
            $table->timestamps();

            $table->foreign('address_id')->references('id')->on('addresses');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('shop_locations');
    }
}
