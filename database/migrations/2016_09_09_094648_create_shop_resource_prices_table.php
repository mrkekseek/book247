<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShopResourcePricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shop_resource_prices', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('resource_id')->unsigned();
            $table->string('days', 50);
            $table->time('time_start');
            $table->time('time_stop');
            $table->date('date_start')->nullable();
            $table->date('date_stop')->nullable();
            $table->enum('type',['general', 'specific'])->default('general');
            $table->float('price');
            $table->integer('vat_id')->unsigned();
            $table->timestamps();
        });

        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::table('shop_resource_prices', function($table) {
            $table->foreign('resource_id')
                ->references('id')
                ->on('shop_resources')
                ->onDelete('cascade');
            $table->foreign('vat_id')->references('id')->on('vat_rates');
        });
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('shop_resource_prices');
    }
}
