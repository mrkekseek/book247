<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookingInvoiceItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booking_invoice_items', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->integer('booking_invoice_id')->unsigned();
            $table->integer('booking_id')->unsigned();
            $table->timestamps();

            $table->foreign('booking_invoice_id')->references('id')->on('booking_invoices');
            $table->foreign('booking_id')->references('id')->on('bookings');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('booking_invoice_items');
    }
}
