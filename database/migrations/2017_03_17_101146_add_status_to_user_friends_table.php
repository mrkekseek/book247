<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusToUserFriendsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_friends', function (Blueprint $table) {
            DB::statement("ALTER TABLE `user_friends` ADD `status` ENUM('pending','active','blocked','') NOT NULL DEFAULT 'active' AFTER `friend_id`;");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_friends', function (Blueprint $table) {
            DB::statement("ALTER TABLE `user_friends` DROP COLUMN `status`;");
        });
    }
}
