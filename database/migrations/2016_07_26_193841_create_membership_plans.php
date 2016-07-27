<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMembershipPlans extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('membership_plans', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->enum('status', ['active, pending, suspended, deleted']);
            $table->integer('price_id')->unsigned();
            $table->timestamps();

            $table->foreign('price_id')->references('id')->on('membership_plan_prices');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('membership_plans');
    }
}
