<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUserStatusToOptimizeSearchMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('optimize_search_members', function (Blueprint $table) {
            $table->char('user_status',50)->nullable()->after('email');
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
            $table->dropColumn(['user_status']);
        });
    }
}