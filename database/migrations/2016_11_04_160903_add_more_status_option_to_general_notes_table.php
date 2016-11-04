<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMoreStatusOptionToGeneralNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('general_notes', function (Blueprint $table) {
            DB::statement("ALTER TABLE `general_notes` CHANGE `status` `status` ENUM('unread','read','deleted','completed','pending');");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('general_notes', function (Blueprint $table) {
            DB::statement("ALTER TABLE `general_notes` CHANGE `status` `status` ENUM('unread','read','deleted');");
        });
    }
}
