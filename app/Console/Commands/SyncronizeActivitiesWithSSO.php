<?php

namespace App\Console\Commands;

use App\Http\Libraries\ApiAuth;
use Illuminate\Console\Command;

class SyncronizeActivitiesWithSSO extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sso:syncronize_activities';

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
        $activities = ApiAuth::getActivities();
        if ($activities){
            foreach ($activities as $single){

            }
        }
        else{

        }
    }
}
