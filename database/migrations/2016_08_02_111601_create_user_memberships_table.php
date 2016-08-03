<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserMembershipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_memberships', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('membership_id')->unsigned();
            $table->date('day_start');
            $table->date('day_stop');
            $table->string('membership_name');
            $table->integer('invoice_period');
            $table->float('price');
            $table->float('discount');
            $table->longText('membership_restrictions');
            $table->integer('signed_by')->unsigned();       // the employee that assigned the membership or the user himself
            $table->enum('status',['active','suspended','canceled','expired']);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('signed_by')->references('id')->on('users');
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
        Schema::drop('user_memberships');
    }
}
