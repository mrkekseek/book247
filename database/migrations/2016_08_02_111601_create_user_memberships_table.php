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
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->date('day_start');
            $table->date('day_stop');
            $table->string('membership_name');
            $table->integer('invoice_period');
            $table->float('price');
            $table->float('discount');
            $table->longText('membership_restrictions');
            $table->enum('status', ['active', 'suspended', 'canceled', 'expired']);
            $table->timestamps();
        });

        Schema::table('user_memberships', function($table){
            $table->integer('user_id')->unsigned()->nullable();
            $table->foreign('user_id')->references('id')->on('users');

            $table->integer('signed_by')->unsigned()->nullable();       // the employee that assigned the membership or the user himself
            $table->foreign('signed_by')->references('id')->on('users');

            $table->integer('membership_id')->unsigned()->nullable();
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
