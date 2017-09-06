<?php

namespace App\Console\Commands\Fixes;

use App\PersonalDetail;
use Illuminate\Console\Command;
use App\User;


class NormalizeUserData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'normalize_user_data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Change user email from uppercase to lowercase.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $users = User::all();
        foreach ($users as $user) {
            if (isset($user->email)) {
                $user->email = strtolower($user->email);
            }
            if (isset($user->username)) {
                $user->username = strtolower($user->username);
            }
            $user->save();
            $personal_details = PersonalDetail::where('user_id',$user->id)->first();
            if ($personal_details) {
                $personal_details->personal_email = $user->email;
                $personal_details->save();
            }
        }
    }
}
