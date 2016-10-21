<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusToUserMembershipActionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_membership_actions', function (Blueprint $table) {
            $table->enum('status', ['active','old','cancelled'])->after('processed')->default('active');
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
            $table->dropColumn(['status']);
        });
    }
}
