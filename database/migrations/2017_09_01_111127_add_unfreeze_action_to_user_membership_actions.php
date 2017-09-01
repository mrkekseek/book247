<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUnfreezeActionToUserMembershipActions extends Migration
{


    public function up()
    {
        \DB::statement("ALTER TABLE user_membership_actions MODIFY COLUMN action_type ENUM('freeze', 'cancel', 'update', 'unknown','unfreeze')");
    }

    public function down()
    {
        DB::statement("ALTER TABLE user_membership_actions MODIFY COLUMN action_type ENUM('freeze', 'cancel', 'update', 'unknown')");
    }
}
