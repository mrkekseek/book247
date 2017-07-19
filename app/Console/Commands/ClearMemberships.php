<?php

namespace App\Console\Commands;

use App\UserMembership;
use Illuminate\Console\Command;
use App\User;

class ClearMemberships extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'memberships:clear_pending';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear pending memberships.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $memberships = UserMembership::where('status','pending')->get();
        foreach($memberships as $membership) {
            $t = strtotime($membership->updated_at);
            $time_difference = (time() - $t)/3600;
            if ($time_difference > 24) {
                $user = User::find($membership->user_id);
                $user->cancel_membership_plan($membership);
            }
        }
    }
}