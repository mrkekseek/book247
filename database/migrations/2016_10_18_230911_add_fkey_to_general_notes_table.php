<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFkeyToGeneralNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('general_notes', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->foreign('by_user_id')->references('id')->on('users');
            $table->foreign('for_user_id')->references('id')->on('users');
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
            $table->dropForeign(['by_user_id']);
            $table->dropForeign(['for_user_id']);
        });
    }
}
