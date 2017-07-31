<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoreCreditPurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('store_credit_purchases', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->integer('employee_id')->unsigned()->nullable();
            $table->integer('store_credit_pack_id')->unsigned();
            $table->foreign('store_credit_pack_id')->references('id')->on('store_credit_products');
            $table->integer('invoice_id')->unsigned();
            $table->foreign('invoice_id')->references('id')->on('invoices');
            $table->enum('status', ['pending', 'paid', 'cancelled', 'deleted']);
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
        Schema::drop('store_credit_purchases');
    }
}
