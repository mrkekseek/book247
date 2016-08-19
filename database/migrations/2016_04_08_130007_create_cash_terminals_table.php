<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCashTerminalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cash_terminals', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->string('name', 64);
            $table->string('bar_code', 64);
            $table->integer('location_id')->unsigned();
            $table->enum('status', ['active','suspended','cancelled']);
            $table->timestamps();

            $table->foreign('location_id')->references('id')->on('shop_locations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('cash_terminals');
    }
}
