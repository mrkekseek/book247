<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDefaultTimeIntervalToShopeResourceCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shop_resource_categories', function (Blueprint $table) {
            $table->integer('default_time_interval')->default(5)->after('url');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shop_resource_categories', function (Blueprint $table) {
            $table->dropColumn('default_time_interval');
        });
    }
}
