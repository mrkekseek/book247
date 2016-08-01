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
            $table->integer('price_id');
            $table->string('plan_calendar_color');
            $table->enum('status', ['active', 'pending', 'suspended', 'deleted']);
            $table->integer('plan_period');
            $table->string('administration_fee_name');
            $table->float('administration_fee_amount');
            $table->text('short_description');
            $table->longText('description');
            $table->timestamps();
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
