<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSignOutPeriodToUserMembershipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_memberships', function (Blueprint $table) {
            $table->integer('sign_out_period')->after('binding_period');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_memberships', function (Blueprint $table) {
            $table->dropColumn('sign_out_period');
        });
    }
}