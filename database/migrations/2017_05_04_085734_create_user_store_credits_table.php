<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserStoreCreditsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_store_credits', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->integer('member_id')->unsigned();
            $table->integer('back_user_id')->nullable();
            $table->char('title');
            $table->integer('value');
            $table->integer('total_amount')->nullable();
            $table->integer('invoice_id')->nullable();
            $table->date('expiration_date')->nullable();
            $table->enum('status', ['active','expired','deleted','spent']);
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
        Schema::drop('user_store_credits');
    }
}
