<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMembershipIdToOptimizeSearchMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('optimize_search_members', function (Blueprint $table) {
            $table->integer('membership_id')->nullable()->after('region');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('optimize_search_members', function (Blueprint $table) {
            $table->dropColumn(['membership_id']);
        });
    }
}
