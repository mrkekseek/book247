<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFkSinancialProfilesToShopFinancialProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shop_financial_profiles', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->foreign('financial_profile_id')->references('id')->on('financial_profiles');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shop_financial_profiles', function (Blueprint $table) {
            $table->dropForeign(['financial_profile_id']);
        });
    }
}
