<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvoiceFinancialTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_financial_transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('invoice_id')->unsigned();
            $table->double('transaction_amount');
            $table->char('transaction_currency',3);
            $table->enum('transaction_type', ['cash', 'card', 'manual'])->default('manual');
            $table->date('transaction_date');
            $table->enum('status', ['pending','processing','completed','cancelled','declined','incomplete'])->default('processing');
            $table->text('other_details');
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
        Schema::drop('invoice_financial_transactions');
    }
}
