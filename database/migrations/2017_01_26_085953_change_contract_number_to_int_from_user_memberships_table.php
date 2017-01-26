<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeContractNumberToIntFromUserMembershipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_memberships', function (Blueprint $table) {
            DB::statement('ALTER TABLE `user_memberships` CHANGE `contract_number` `contract_number` INT(11) NULL DEFAULT NULL;');
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
            DB::statement('ALTER TABLE `user_memberships` CHANGE `contract_number` `contract_number` CHAR(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL;');
        });
    }
}
