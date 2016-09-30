<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserMembershipInvoicePlanningTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_membership_invoice_planning', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->integer('user_membership_id')->unsigned();
            $table->string('item_name', 500);
            $table->double('price');
            $table->double('discount');
            $table->date('issued_date');
            $table->enum('status',['old','pending','last']);
            $table->timestamps();
        });

        Schema::table('user_membership_invoice_planning', function (Blueprint $table) {
            DB::statement('SET FOREIGN_KEY_CHECKS = 0');
            $table->foreign('user_membership_id')->references('id')->on('user_memberships');
            DB::statement('SET FOREIGN_KEY_CHECKS = 1');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('user_membership_invoice_planning');
    }
}
