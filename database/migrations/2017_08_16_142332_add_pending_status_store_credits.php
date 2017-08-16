<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPendingStatusStoreCredits extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        \Schema::table('user_memberships', function (Blueprint $table) {
        \DB::statement("ALTER TABLE user_store_credits MODIFY COLUMN status ENUM('active', 'expired', 'deleted', 'spent','pending')");
//            $table->enum('status' ,['active', 'suspended', 'canceled', 'expired','pending'])->change();
//            $table->enum('status',['active','suspended','canceled','expired','pending'])
//        });
    }

    public function down()
    {
//        Schema::table('user_memberships', function (Blueprint $table) {
        DB::statement("ALTER TABLE user_store_credits MODIFY COLUMN status ENUM('active', 'expired', 'deleted', 'spent')");
//        });
    }
}
