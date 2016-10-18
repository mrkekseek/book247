<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFkeyToUserMembershipActionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_membership_actions', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->foreign('user_membership_id')->references('id')->on('user_memberships');
            $table->foreign('added_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_membership_actions', function (Blueprint $table) {
            $table->dropForeign(['user_membership_id']);
            $table->dropForeign(['added_by']);
        });
    }
}
