<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddInvoiceItemIdToInvoiceFinancialTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invoice_financial_transactions', function (Blueprint $table) {
            $table->string('invoice_items')->after('invoice_id');
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
            $table->dropColumn(['invoice_items']);
        });
    }
}
