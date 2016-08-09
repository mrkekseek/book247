<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvoiceItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_items', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->integer('invoice_id')->unsigned();
            $table->string('item_name');
            $table->string('item_type'); // table name
            $table->integer('item_reference_id');
            $table->tinyInteger('quantity');
            $table->float('price');
            $table->float('vat');
            $table->float('discount');
            $table->float('total_price');
            $table->timestamps();
        });

        Schema::table('invoice_items', function($table){
            $table->foreign('invoice_id')->references('id')->on('invoices');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('invoice_items');
    }
}
