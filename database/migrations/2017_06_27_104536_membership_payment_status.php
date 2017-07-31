<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
//use Doctrine\DBAL\Schema\Schema;

class MembershipPaymentStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        \Schema::table('user_memberships', function (Blueprint $table) {
            \DB::statement("ALTER TABLE user_memberships MODIFY COLUMN status ENUM('active', 'suspended', 'canceled', 'expired','pending')");
//            $table->enum('status' ,['active', 'suspended', 'canceled', 'expired','pending'])->change();
//            $table->enum('status',['active','suspended','canceled','expired','pending'])
//        });
    }

    public function down()
    {
//        Schema::table('user_memberships', function (Blueprint $table) {
            DB::statement("ALTER TABLE user_memberships MODIFY COLUMN status ENUM('active', 'suspended', 'canceled', 'expired')");
//        });
    }
}
