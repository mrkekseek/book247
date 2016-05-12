<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMoreStatusOptionsForBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bookings', function (Blueprint $table) {
            /**
             * pending = this booking is doing now
             * active  = booking is saved and confirmed
             * paid    = this booking was a paid booking and the invoice was paid; the booking is old
             * unpaid  = this booking was a paid booking and the invoice was paid; the booking is old
             * old     = this booking passed and is marked as old, from the past
             * canceled= this booking was canceled in the approved time
             */
            //$table->enum('status',['pending','active','paid','unpaid','old','canceled'])->change();
            DB::statement("ALTER TABLE `bookings` CHANGE `status` `status` ENUM('pending','active','paid','unpaid','old','canceled') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bookings', function (Blueprint $table) {
            //$table->enum('status',['pending','active','paid','canceled'])->change();
        });
    }
}
