<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserMembershipActionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_membership_actions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_membership_id')->unsigned()->nullable();
            $table->enum('action_type',['freeze','cancel']);
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('added_by')->unsigned()->nullable();
            $table->string('notes');
            $table->tinyInteger('processed')->default(0);

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
        Schema::drop('user_membership_actions');
    }
}
