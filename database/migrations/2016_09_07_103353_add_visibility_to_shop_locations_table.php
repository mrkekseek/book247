<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddVisibilityToShopLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shop_locations', function (Blueprint $table) {
            $table->enum('visibility',['warehouse','public','pending','suspended'])->after('registered_no')->default('pending');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shop_locations', function (Blueprint $table) {
            $table->dropColumn('visibility');
        });
    }
}
