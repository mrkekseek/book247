<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookingFinancialTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booking_financial_transactions', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('booking_invoice_id')->unsigned();
            $table->float('transaction_amount');
            $table->string('transaction_currency', 3);
            $table->string('transaction_type');
            $table->dateTime('transaction_date');
            $table->enum('status', ['pending','processing','completed','cancelled','declined','incomplete']);
            $table->text('other_details');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('booking_invoice_id')->references('id')->on('booking_invoices');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('booking_financial_transactions');
    }
}
