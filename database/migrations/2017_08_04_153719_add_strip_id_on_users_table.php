<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStripIdOnUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('stripe_id', 255)->nullable()->after('sso_user_id');
            $table->string('card_brand', 255)->nullable()->after('stripe_id');
            $table->integer('card_last_four')->unsigned()->after('card_brand');
            $table->timestamp('trial_ends_at')->nullable()->after('card_last_four');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
             $table->dropColumn('stripe_id');
             $table->dropColumn('card_brand');
             $table->dropColumn('card_last_four');
             $table->dropColumn('trial_ends_at');
        });
    }
}
