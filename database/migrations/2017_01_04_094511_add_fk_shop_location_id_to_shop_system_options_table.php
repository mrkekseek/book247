<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFkShopLocationIdToShopSystemOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shop_system_options', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->foreign('shop_location_id')->references('id')->on('shop_locations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shop_system_options', function (Blueprint $table) {
            $table->dropForeign(['shop_location_id']);
        });
    }
}
