<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAdditionalValuesToUserMembershipActionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_membership_actions', function (Blueprint $table) {
            $table->string('additional_values', 600)->default('[]')->after('action_type');
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
            $table->dropColumn(['additional_values']);
        });
    }
}
