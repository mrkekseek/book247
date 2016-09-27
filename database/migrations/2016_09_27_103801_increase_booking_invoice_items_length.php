<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class IncreaseBookingInvoiceItemsLength extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('booking_invoice_items', function (Blueprint $table) {
            $table->string('location_name', 200)->change();
            $table->string('resource_name', 200)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('booking_invoice_items', function (Blueprint $table) {
            //
        });
    }
}
