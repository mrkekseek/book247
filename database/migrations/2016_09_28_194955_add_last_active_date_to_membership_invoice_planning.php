<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLastActiveDateToMembershipInvoicePlanning extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_membership_invoice_planning', function (Blueprint $table) {
            $table->date('last_active_date')->after('issued_date');
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
            $table->dropColumn('last_active_date');
        });
    }
}
