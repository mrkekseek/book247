<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsLockedToShopLocationCategoryIntervalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shop_location_category_intervals', function (Blueprint $table) {
            $table->enum('is_locked', ['0','1'])->default('0')->after('time_interval');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shop_location_category_intervals', function (Blueprint $table) {
            $table->dropColumn('is_locked');
        });
    }
}
