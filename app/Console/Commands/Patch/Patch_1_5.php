<?php

namespace App\Console\Commands\Patch;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class Patch_1_5 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'patch:number1_5';

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
            [32, 'Finance Paypal Account Key', 'finance_paypal_account_key', 'Advanced PayPal integration - this is your account key', 0, 'string', 15, 60, 0],
            [33, 'Finance Paypal Account Secret', 'finance_paypal_account_secret', 'Advanced PayPal integration - account secret. This is paired with PayPal Key.', 0, 'string', 2, 60, 0],
            [34, 'Finance Stripe Account Key', 'finance_stripe_account_key', 'Stripe payment integration - Account key', 0, 'string', 5, 60, 0],
            [35, 'Finance Stripe Account Secret', 'finance_stripe_account_secret', 'Stripe payment integration - Account Secret', 0, 'string', 5, 60, 0],
            [36, 'Global Website Registration Email Validation', 'globalWebsite_registration_email_validation', 'If set to yes, all new member registration will require email verification : an email will be sent to them to activate their accounts.', 1, '', 0, 0, 0]
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

        $allowedSettingInsertValues = [
            [24, 36, 'Yes',  '1'],
            [25, 36, 'No',  '0']
        ];

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
        DB::insert($query, [36,  25,  NULL, 1]);
        // Add new general settings values - Stop
    }
}
