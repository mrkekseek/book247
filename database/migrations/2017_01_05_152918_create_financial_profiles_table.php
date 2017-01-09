<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFinancialProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('financial_profiles', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->string('company_name', 150);
            $table->string('bank_name', 150);
            $table->string('bank_account', 75);
            $table->string('organisation_number', 75);
            $table->string('address1', 150);
            $table->string('address2', 100);
            $table->string('city', 70);
            $table->string('postal_code', 25);
            $table->string('region', 75);
            $table->integer('country')->length(11);
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
        Schema::drop('financial_profiles');
    }
}
