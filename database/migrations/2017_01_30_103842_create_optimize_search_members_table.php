<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOptimizeSearchMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('optimize_search_members', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->integer('user_id');
            $table->char('first_name', 255);
            $table->char('middle_name', 255)->nullable();
            $table->char('last_name', 255);

            $table->char('email', 255);
            $table->char('phone', 30);

            $table->char('city', 30)->nullable();
            $table->char('region', 30)->nullable();

            $table->char('membership_name', 75);
            $table->char('user_profile_image', 255);

            $table->char('user_link_details', 255);

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
        Schema::drop('optimize_search_members');
    }
}
