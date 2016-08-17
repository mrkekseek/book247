<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSpecialRestrictionsToMembershipRestrictionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('membership_restrictions', function (Blueprint $table) {
            $table->text('special_permissions')->after('time_end');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('membership_restrictions', function (Blueprint $table) {
            $table->dropColumn('special_permissions');
        });
    }
}
