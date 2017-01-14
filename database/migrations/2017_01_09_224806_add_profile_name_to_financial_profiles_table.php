<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddProfileNameToFinancialProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('financial_profiles', function (Blueprint $table) {
            $table->string('profile_name', 150);
            $table->unique('profile_name');
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
            $table->dropUnique(['profile_name']);
            $table->dropColumn(['profile_name']);
        });
    }
}
