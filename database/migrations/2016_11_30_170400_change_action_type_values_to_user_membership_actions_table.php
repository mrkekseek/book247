<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeActionTypeValuesToUserMembershipActionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_membership_actions', function (Blueprint $table) {
            DB::statement("ALTER TABLE `user_membership_actions` CHANGE `action_type` `action_type` ENUM('freeze','cancel','update','unknown')");
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
            DB::statement("ALTER TABLE `user_membership_actions` CHANGE `action_type` `action_type` ENUM('freeze','cancel')");
        });
    }
}
