<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeTypeMinMax extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE `settings` CHANGE `min_value` `min_value` FLOAT');
        DB::statement('ALTER TABLE `settings` CHANGE `max_value` `max_value` FLOAT');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('ALTER TABLE `settings` CHANGE `min_value` `min_value` VARCHAR(25) NULL');
        DB::statement('ALTER TABLE `settings` CHANGE `max_value` `max_value` VARCHAR(25) NULL');
    }
}
