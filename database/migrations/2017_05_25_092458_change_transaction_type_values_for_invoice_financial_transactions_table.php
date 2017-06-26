<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeTransactionTypeValuesForInvoiceFinancialTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invoice_financial_transactions', function (Blueprint $table) {
            DB::statement("ALTER TABLE `invoice_financial_transactions` CHANGE `transaction_type` `transaction_type` ENUM('cash','card','credit','manual')");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('invoice_financial_transactions', function (Blueprint $table) {
            DB::statement("ALTER TABLE `invoice_financial_transactions` CHANGE `transaction_type` `transaction_type` ENUM('cash','card','manual')");
        });
    }
}
