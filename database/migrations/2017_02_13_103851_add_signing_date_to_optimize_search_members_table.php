<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSigningDateToOptimizeSearchMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('optimize_search_members', function (Blueprint $table) {
            $table->addColumn('date', 'signing_date')->nullable()->after('membership_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('optimize_search_members', function (Blueprint $table) {
            $table->dropColumn(['signing_date']);
        });
    }
}
