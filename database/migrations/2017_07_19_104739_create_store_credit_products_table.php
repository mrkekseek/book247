<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoreCreditProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_credit_products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 150)->nullable();
            $table->string('description', 255)->nullable();
            $table->integer('store_credit_value')->unsigned();
            $table->float('store_credit_price', 10, 2)->unsigned();
            $table->integer('store_credit_discount_fixed')->unsigned();
            $table->integer('store_credit_discount_percentage')->unsigned();
            $table->integer('validity_days')->unsigned();
            $table->date('valid_from');
            $table->date('valid_to');
            $table->integer('packages_per_user')->unsigned();
            $table->enum('status', ['active', 'pending', 'suspended', 'deleted']);
            $table->integer('added_by')->unsigned();

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
        //
    }
}
