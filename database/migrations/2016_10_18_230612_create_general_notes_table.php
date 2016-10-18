<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGeneralNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('general_notes', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->integer('by_user_id')->unsigned();
            $table->integer('for_user_id')->unsigned();
            $table->string('note_title', 150);
            $table->text('note_body');
            $table->string('note_type', 100);
            $table->enum('privacy',['admin','employees','everyone']);
            $table->enum('status' ,['unread','read','deleted']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('general_notes');
    }
}
