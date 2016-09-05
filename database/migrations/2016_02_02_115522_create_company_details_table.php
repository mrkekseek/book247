<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompanyDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_details', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->string('company_name', 150);
            $table->integer('address_id')->unsigned();
            $table->string('website', 250);
            $table->string('alert_email', 250);
            $table->string('background', 250);
            $table->string('color', 250);
            $table->string('banner', 250);
            $table->string('logo_small', 250);
            $table->string('logo_big', 250);
            $table->integer('country_id')->unsigner();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('company_details');
    }
}
