<?php

namespace App\Console\Commands;

use App\Http\Libraries\ApiAuth;
use App\User;
use Illuminate\Console\Command;

class CheckLocalAccountsAgainstSSO extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'localAccounts:checkAgainstSSO';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $users = User::whereNotNull('sso_user_id')->get();
        echo sizeof($users).' users available'.PHP_EOL;
        foreach($users as $user){
            if ($user->sso_user_id==null){
                echo 'For user '.$user->username.' we skipped verifications '.PHP_EOL;
                continue;
            }

            $response = ApiAuth::accounts_get_by_username($user->username);
            if ($response['success']==false){
                echo 'For user '.$user->username.' no SSO record was found '.PHP_EOL;
                continue;
            }

            $ssoUser = $response['data'];
            $isChange = false;

            if (strtolower($user->email)!=strtolower($ssoUser->email)){
                $isChange = true;
            }

            if (strtolower($user->username)!=strtolower($ssoUser->username)){
                $isChange = true;
            }

            if ($user->sso_user_id!=$ssoUser->id){
                $isChange = true;
            }

            if ($isChange){
                echo '### Local '.$user->id.' : ssoID - '.$user->sso_user_id.' , email: '.$user->email.' , username : '.$user->username.PHP_EOL;
                echo '### SSO : ssoID - '.$ssoUser->id.' , email: '.$ssoUser->email.' , username : '.$ssoUser->username.PHP_EOL;
            }
            else{
                //echo 'Local User['.$user->id.'] OK : ssoID - ['.$user->sso_user_id.'/'.$ssoUser->id.'] , email: '.$user->email.' , username : '.$user->username.PHP_EOL;
            }
        }
    }
}
