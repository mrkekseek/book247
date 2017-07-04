<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class NullMiddlename extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE `users` CHANGE `middle_name` `middle_name` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('ALTER TABLE `users` CHANGE `middle_name` `middle_name` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;');
    }
}
