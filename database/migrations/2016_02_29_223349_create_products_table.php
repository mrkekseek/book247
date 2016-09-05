<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->string('name');
            $table->string('alternate_name');
            $table->integer('category_id')->unsigned();
            $table->string('brand');
            $table->string('color');
            $table->string('logo');
            $table->string('manufacturer');
            $table->string('description');
            $table->string('url');
            $table->string('barcode');
            $table->tinyInteger('status');
            $table->integer('vat_rate_id')->unsigned();
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
        Schema::drop('products');
    }
}
