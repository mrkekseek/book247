<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddVatRateToShopResourcesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shop_resources', function (Blueprint $table) {
            $table->integer('vat_id')->unsigned()->after('session_price')->default('1');
        });

        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::table('shop_resource_prices', function($table) {
            $table->foreign('vat_id')->references('id')->on('vat_rates');
        });
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shop_resources', function (Blueprint $table) {
            $table->dropColumn('vat_id');
        });
    }
}
