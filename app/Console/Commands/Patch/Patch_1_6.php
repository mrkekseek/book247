<?php

namespace App\Console\Commands\Patch;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Patch_1_6 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'patch:number1_6';

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
        // seed emails again - updated and changed
        $seeder = new \EmailTemplateSeeder();
        $seeder->run();

        // add new general settings values - Start
        $settingsInsertValues = [
            [37, 'Global Website Custm Terms and Agreements', 'globalWebsite_custom_terms_and_agreements', 'In this field you can place the terms and agreements from your own website for buying memberships and other financial packages.', 0, 'string', 5, 200, 0],
            [38, 'Global Website Facebook FeedLink', 'globalWebsite_facebook_feedLink', 'Facebook account link for showing homepage Facebook embeded feed from facebook page', 0, 'string', 5, 220, 0],
            [39, 'Global Website Account Logo Image', 'globalWebsite_account_logo_image', 'This is the image that will be used as logo in all the application places', 0, 'string', 5, 255, 0]
        ];

        // insert values into settings table
        foreach ($settingsInsertValues as $single){
            DB::table('settings')->insert([
                'id'            => $single[0],
                'name'          => $single[1],
                'system_internal_name'  => $single[2],
                'description'   => $single[3],
                'constrained'   => $single[4],
                'data_type'     => $single[5],
                'min_value'     => $single[6],
                'max_value'     => $single[7],
                'is_protected'  => $single[8],
                'created_at'    => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'    => Carbon::now()->format('Y-m-d H:i:s')
            ]);
        }

        $allowedSettingInsertValues = [];

        // insert values into allowed_setting_values
        foreach($allowedSettingInsertValues as $single){
            DB::table('allowed_setting_values')->insert([
                'id'        => $single[0],
                'setting_id'=> $single[1],
                'item_value'=> $single[2],
                'caption'   => $single[3],
                'created_at'=> Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'=> Carbon::now()->format('Y-m-d H:i:s')
            ]);
        }

        // we set up the domain for the application and some other default base settings
        $query = "INSERT INTO application_settings (setting_id, allowed_setting_value_id, unconstrained_value, updated_by_id) VALUES (?,?,?,?)";

        // text values that needs to be inputed by the owner
        //DB::insert($query, [1,  NULL, env('URL'),   1]);

        // confined values
        //DB::insert($query, [36,  25,  NULL, 1]);
        // Add new general settings values - Stop
    }
}
