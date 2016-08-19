<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_invoices', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->integer('order_id')->unsigned();
            $table->string('invoice_number', 64);
            $table->text('other_details');
            $table->enum('status', ['pending','ordered','processing','completed','cancelled','declined','incomplete','preordered']);
            $table->timestamps();

            $table->foreign('order_id')->references('id')->on('orders');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('order_invoices');
    }
}
