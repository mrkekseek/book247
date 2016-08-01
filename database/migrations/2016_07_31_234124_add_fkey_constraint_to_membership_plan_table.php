<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFkeyConstraintToMembershipPlanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //Schema::table('membership_plans', function($table) {
        //    $table->integer('price_id')->unsigned();
        //    $table->foreign('price_id')->references('id')->on('membership_plan_prices');
        //});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //Schema::table('membership_plans', function($table) {
        //    $table->dropForeign('price_id');
        //});
    }
}
