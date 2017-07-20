<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class IncreaseInvoicePayInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        DB::statement('ALTER TABLE `invoices` MODIFY COLUMN `payer_info` VARCHAR(2000)');
        DB::statement('ALTER TABLE `invoices` MODIFY COLUMN `payee_info` VARCHAR(2000)');

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->string('payer_info', 600)->after('invoice_number')->nullable();
            $table->string('payee_info', 600)->after('invoice_number')->nullable();
        });
    }
}
