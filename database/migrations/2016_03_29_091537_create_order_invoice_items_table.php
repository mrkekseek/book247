<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderInvoiceItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_invoice_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('invoice_id')->unsigned();
            $table->integer('order_item_id')->unsigned();
            $table->integer('product_id')->unsigned();
            $table->string('product_name');
            $table->integer('product_quantity');
            $table->float('product_cost');
            $table->float('product_price');
            $table->float('product_manual_price');
            $table->integer('product_vat');
            $table->float('product_total_cost');
            $table->timestamps();

            $table->foreign('invoice_id')->references('id')->on('order_invoices');
            $table->foreign('order_item_id')->references('id')->on('order_items');
            $table->foreign('product_id')->references('id')->on('products');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('order_invoice_items');
    }
}
