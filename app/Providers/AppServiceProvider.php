<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Http\Controllers\AppSettings;
use Illuminate\Support\Facades\Config;
use Illuminate\Contracts\Cache\Repository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // we set up the config constants file dynamically
        /*$value = AppSettings::get_setting_value_by_name('globalWebsite_baseUrl');
        if ($value!==false){
            Config::set('constants.globalWebsite.baseUrl', $value);
        }

        $value = AppSettings::get_setting_value_by_name('globalWebsite_url');
        if ($value!==false){
            Config::set('constants.globalWebsite.url', $value);
        }

        'globalWebsite' => [
            'url'               => env('MY_SERVER_URL','http://bookingsys/'),
            'defaultCountryId'  => 578,
            'baseUrl'           => 'http://bookingsys/',
            'system_email'      => 'booking_agent@book247.net',
            'auto_show_status_change'       => 0,
            'email_company_name_in_title'   => 'SQF'
            // etc
        ],
        'finance' => [
            'currency'              => 'NOK',
            'store_credit_validity' => 6
        ]*/
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
