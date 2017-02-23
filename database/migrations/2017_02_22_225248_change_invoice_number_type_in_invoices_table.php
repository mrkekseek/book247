<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeInvoiceNumberTypeInInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invoices', function (Blueprint $table) {
            DB::statement("ALTER TABLE `invoices` CHANGE `invoice_number` `invoice_number` INT(11) NOT NULL;");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('invoices', function (Blueprint $table) {
            DB::statement("ALTER TABLE `invoices` CHANGE `invoice_number` `invoice_number` VARCHAR(64) NOT NULL;");
        });
    }
}
