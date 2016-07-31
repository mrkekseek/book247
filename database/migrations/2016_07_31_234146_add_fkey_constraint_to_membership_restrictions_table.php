<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFkeyConstraintToMembershipRestrictionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('membership_restrictions', function (Blueprint $table) {
            $table->integer('restriction_id')->unsigned();
            $table->foreign('restriction_id')->references('id')->on('membership_restriction_types');
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
            $table->dropForeign('restriction_id');
        });
    }
}
