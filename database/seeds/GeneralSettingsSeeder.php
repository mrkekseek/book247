<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class GeneralSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // delete old data from settings and
        DB::table('settings')->delete();
        DB::unprepared('ALTER TABLE settings AUTO_INCREMENT=1;');

        DB::table('allowed_setting_values')->delete();
        DB::unprepared('ALTER TABLE allowed_setting_values AUTO_INCREMENT=1;');

        DB::table('application_settings')->delete();
        DB::unprepared('ALTER TABLE application_settings AUTO_INCREMENT=1;');

        $settingsInsertValues = [
            [1, 'Global Website Url', 'globalWebsite_url', 'This is the URL of the website/domain', 0, 'string', 0, 0],
            [2, 'Global Website Country', 'globalWebsite_defaultCountryId', 'This is the default/base country for the account', 0, 'string', 0, 0],
            [3, 'Global Website baseUrl', 'globalWebsite_baseUrl', 'This is the base url of the subdomain/account', 0, 'string', 0, 0],
            [4, 'Global Website System Email', 'globalWebsite_system_email', 'This is the email address that will be used to send emails to users on behalf of the system.', 0, 'string', 0, 0],
            [5, 'Global Website Auto Show Status Change', 'globalWebsite_auto_show_status_change', 'If set to yes, this will mark the passed bookings as shown , automatically. If set to no, then marking as shown will be made by one of the backend employee.', 1, '', 0, 0],
            [6, 'Global Website Company name in email title', 'globalWebsite_email_company_name_in_title', 'What is the company or club name that you want to be shown in email title', 0, 'string', 0, 0],
            [7, 'Finance Currency', 'finance_currency', 'Default currency that will be used in transactions and invoices', 0, 'string', 0, 0],
            [8, 'Finance Store Credit Validity', 'finance_store_credit_validity', 'How much time will the store credit be valide. This is a numeric value and represents the number of months from the moment of purchase', 0, 'numeric', 1, 999],
            [9, 'Booking Cancellation rule', 'bookings_cancellation_before_time_rule', 'This setting decides how early a non-member has to cancel his reservation. The values is in hours between 1 and 48', 0, 'numeric', 1, 48],
            [10, 'Booking Upfront Reservation rule', 'bookings_upfront_reservation_rule', 'This setting decides how early a non member may reserve a court. The value represent the number of days.', 0, 'numeric', 0, 14],
            [11, 'Show Calendar Availability on Frontend rule', 'show_calendar_availability_rule', 'This setting decides if customers who visits you booking calendar hos to be logged in to see calendar availablity.', 1, '', 0, 0],
            [12, 'Bookings Online Payment rule', 'bookings_online_payment_rule', 'This setting decides if all custumers has to pay online directly when reserving a court.', 1, '', 0, 0],
            [13, 'Bookings Court Refunds rule', 'bookings_court_refund_rule', 'This setting decides if a customer will get a refund when canelling a court within the cancelltion rule.', 1, '', 0, 0],
            [14, 'Global Website Rankedin Integration Key', 'globalWebsite_rankedin_integration_key', 'This is the key used to integrate Book247 account to your RankedIn account.', 0, 'string', 29, 29]
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
                'created_at'    => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'    => Carbon::now()->format('Y-m-d H:i:s')
            ]);
        }

        $allowedSettingInsertValues = [
            [1, 5, 'Yes',   '1'],
            [2, 5, 'No',    '0'],
            [3, 6, 'Yes',   '1'],
            [4, 6, 'No',    '0'],
            [5, 11, 'Yes',  '1'],
            [6, 11, 'No',   '0'],
            [7, 12, 'Yes',  '1'],
            [8, 12, 'No',   '0'],
            [9, 13, 'Yes',  '1'],
            [10, 13, 'No',  '0']
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
        $query = "INSERT INTO application_settings (id, setting_id, allowed_setting_value_id, unconstrained_value, updated_by_id) VALUES (?,?,?,?,?)";

        DB::insert($query, [1,   1, NULL, env('URL'), 1]);
        DB::insert($query, [3,   2, NULL, '578', 1]);
        DB::insert($query, [2,   3, NULL, env('MY_SERVER_URL'), 1]);
        DB::insert($query, [4,   4, NULL, 'booking_agent@book247.net', 1]);
        DB::insert($query, [8,   6, NULL, 'SQF', 1]);
        DB::insert($query, [7,   7, NULL, 'NOK', 1]);
        DB::insert($query, [6,   8, NULL, '6',  1]);
        DB::insert($query, [9,   9, NULL, '6',  1]);
        DB::insert($query, [10, 10, NULL, '1',  1]);
        DB::insert($query, [11, 14, NULL, '',   1]);

        DB::unprepared('ALTER TABLE settings AUTO_INCREMENT=10000;');
        DB::unprepared('ALTER TABLE allowed_setting_values AUTO_INCREMENT=10000;');
        DB::unprepared('ALTER TABLE application_settings AUTO_INCREMENT=10000;');

    }
}
