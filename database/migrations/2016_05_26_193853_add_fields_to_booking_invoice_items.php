<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToBookingInvoiceItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('booking_invoice_items', function (Blueprint $table) {
            $table->string('location_name')->after('booking_id');
            $table->string('resource_name')->after('location_name');
            $table->tinyInteger('quantity')->after('resource_name');
            $table->date('booking_date')->after('quantity');
            $table->string('booking_time_interval', 50)->after('booking_date');
            $table->float('price')->after('booking_time_interval');
            $table->tinyInteger('vat')->after('price');
            $table->tinyInteger('discount')->after('vat');
            $table->float('total_price')->after('discount');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
