<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddGroupPriceToShopResourcePricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shop_resource_prices', function (Blueprint $table) {
            $table->char('group_price',64)->nullable()->after('type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shop_resource_prices', function (Blueprint $table) {
            $table->dropColumn('group_price');
        });
    }
}
