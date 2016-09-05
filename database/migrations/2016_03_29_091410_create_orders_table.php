<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->integer('employee_id')->unsigned();
            $table->integer('buyer_id');
            $table->string('order_number', 64);
            $table->integer('discount_type')->unsigned();
            $table->float('discount_amount');
            $table->enum('status', ['pending','ordered','processing','completed','cancelled','declined','incomplete','preordered']);
            $table->timestamps();

            $table->foreign('employee_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('orders');
    }
}
