<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdatePaypalTransactionType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \DB::statement("ALTER TABLE invoice_financial_transactions MODIFY COLUMN transaction_type ENUM('cash', 'card', 'credit', 'manual','paypal')");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE invoice_financial_transactions MODIFY COLUMN transaction_type ENUM('cash', 'card', 'credit', 'manual','paypal')");
    }
}
