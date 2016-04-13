<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShopResourcesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shop_resources', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('location_id')->unsigned();
            $table->string('name', 75);
            $table->text('description');
            $table->integer('category_id')->unsigned();
            $table->string('color_code',10);
            $table->timestamps();

            $table->foreign('location_id')->references('id')->on('shop_locations');
            $table->foreign('category_id')->references('id')->on('shop_resource_categories');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('shop_resources');
    }
}
