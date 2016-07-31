<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMembershipRestrictions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('membership_restrictions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('membership_id')->unsigned();
            $table->string('value', 50);
            $table->string('min_value', 50);
            $table->string('max_value', 50);
            $table->string('time_start', 50);
            $table->string('time_end', 50);
            $table->timestamps();

            $table->foreign('membership_id')->references('id')->on('membership_plans');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('membership_restrictions');
    }
}
