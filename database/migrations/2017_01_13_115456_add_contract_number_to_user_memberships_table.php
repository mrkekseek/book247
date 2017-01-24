<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddContractNumberToUserMembershipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_memberships', function (Blueprint $table) {
            $table->char('contract_number', 50)->nullable()->after('membership_id');
        });

        $all_memberships = \App\UserMembership::all();
        if ($all_memberships){
            $contract_start = 10001;
            foreach ($all_memberships as $membership){
                $membership->contract_number = $contract_start++;
                $membership->save();
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_memberships', function (Blueprint $table) {
            $table->dropColumn('contract_number');
        });
    }
}
