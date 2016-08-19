<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCashTerminalReportsTable extends Migration
{
    public function up()
    {
        Schema::create('cash_terminal_daily_reports', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->integer('terminal_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->decimal('cash_out',8,2);
            $table->decimal('cash_left',8,2);
            $table->enum('type', ['shift_start','shift_end']);
            $table->timestamps();

            $table->foreign('terminal_id')->references('id')->on('cash_terminals');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    public function down()
    {
        Schema::drop('cash_terminal_daily_reports');
    }
}
