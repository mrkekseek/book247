<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFkCountryToFinancialProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('financial_profiles', function (Blueprint $table) {
            DB::statement('SET FOREIGN_KEY_CHECKS = 0');
            $table->foreign('country')->references('id')->on('countries');
            DB::statement('SET FOREIGN_KEY_CHECKS = 1');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('financial_profiles', function (Blueprint $table) {
            $table->dropForeign(['country']);
        });
    }
}
