<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddInvoiceIdToMembershipInvoicePlanningTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_membership_invoice_planning', function (Blueprint $table) {
            $table->integer('invoice_id')->after('issued_date')->default('-1');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_membership_invoice_planning', function (Blueprint $table) {
            $table->dropColumn('invoice_id');
        });
    }
}
