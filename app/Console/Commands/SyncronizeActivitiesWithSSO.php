<?php

namespace App\Console\Commands;

use App\Http\Libraries\ApiAuth;
use App\ShopResourceCategory;
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
        if ($activities['success'] == true){
            $allActivities =$activities['activities'];
            foreach ($allActivities as $single){
                $fillable = ['name' => $single->value, 'url' => urlencode(strtolower($single->value)), 'default_time_interval' => isset($single->timeInterval)?$single->timeInterval:5];

                $isLocalActivity = ShopResourceCategory::where('name','=',$single->value)->get();
                if (sizeof($isLocalActivity)>1){
                    // we found more than one insert ... will skip this and report
                }
                elseif(sizeof($isLocalActivity)==1){
                    // we found it and we'll check the default time interval value
                    $isLocalActivity = $isLocalActivity[0];
                    $isLocalActivity->update($fillable);
                }
                else{
                    // new insert
                    ShopResourceCategory::create($fillable);
                }
            }
        }
        else{
            // error getting activities
        }
    }
}
