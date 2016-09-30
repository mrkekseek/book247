<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBookingInvoiceItemIdToBookingFinancialTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('booking_financial_transactions', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->integer('booking_invoice_item_id')->after('booking_invoice_id')->unsigned();
        });

        Schema::table('booking_financial_transactions', function(Blueprint $table){
            DB::statement('SET FOREIGN_KEY_CHECKS = 0');
            $table->foreign('booking_invoice_item_id')->reference('id')->on('booking_invoice_items');
            DB::statement('SET FOREIGN_KEY_CHECKS = 1');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('booking_financial_transactions', function (Blueprint $table) {
            $table->dropForeign(['booking_invoice_item_id']);
            $table->dropColumn(['booking_invoice_item_id']);
        });
    }
}
