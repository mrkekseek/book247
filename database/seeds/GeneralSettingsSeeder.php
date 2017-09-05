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
            [1, 'Global Website Url', 'globalWebsite_url', 'This is the URL of the website/domain', 0, 'string', 0, 0,1],
            [2, 'Global Website Country', 'globalWebsite_defaultCountryId', 'This is the default/base country for the account', 0, 'string', 0, 0,1],
            [3, 'Global Website baseUrl', 'globalWebsite_baseUrl', 'This is the base URL of the subdomain/account', 0, 'string', 0, 0,1],
            [4, 'Global Website System Email', 'globalWebsite_system_email', 'This is the email address that will be used to send emails to users on behalf of the system.', 0, 'string', 0, 0,1],
            [5, 'Global Website Auto Show Status Change', 'globalWebsite_auto_show_status_change', 'If set to yes, this will mark the passed bookings as shown , automatically. If set to no, then marking as shown will be made by one of the backend employee.', 1, '', 0, 0,0],
            [6, 'Global Website Company name in email title', 'globalWebsite_email_company_name_in_title', 'What is the company or club name that you want to be shown in email title', 0, 'string', 0, 0,0],
            [7, 'Finance Currency', 'finance_currency', 'Default currency that will be used in transactions and invoices', 0, 'string', 0, 0,1],
            [8, 'Finance Store Credit Validity', 'finance_store_credit_validity', 'How much time will the store credit be valide. This is a numeric value and represents the number of months from the moment of purchase', 0, 'numeric', 1, 999,0],
            [9, 'Booking Cancellation rule', 'bookings_cancellation_before_time_rule', 'This setting decides how early a non-member has to cancel his reservation. The values is in hours between 1 and 48', 0, 'numeric', 1, 48,0],
            [10, 'Booking Upfront Reservation rule', 'bookings_upfront_reservation_rule', 'This setting decides how early a non-member may reserve a court. The value represent the number of days.', 0, 'numeric', 0, 14,0],
            [11, 'Show Calendar Availability on Frontend rule', 'show_calendar_availability_rule', 'This setting decides if customers who visits your booking calendar has to be logged in to see calendar availablity.', 1, '', 0, 0,0],
            [12, 'Bookings Online Payment rule', 'bookings_online_payment_rule', 'This setting decides if all customers has to pay online directly when reserving a court.', 1, '', 0, 0,0],
            [13, 'Bookings Court Refunds rule', 'bookings_court_refund_rule', 'This setting decides if a customer will get a refund when cancelling a court within the cancellation rule.', 1, '', 0, 0,0],
            [14, 'Global Website Rankedin Integration Key', 'globalWebsite_rankedin_integration_key', 'This is the key used to integrate Book247 account to your RankedIn account.', 0, 'string', 29, 29,1],
            [15, 'Global Website Show Memberships On Frontend', 'globalWebsite_show_memberships_on_frontend', 'This setting will allow you to show or hide the list of available memberships on frontend. ', 1, '', 0, 0,0],
            [16, 'Global Website Show Invoices on Frontend', 'globalWebsite_show_invoices_on_frontend', 'This setting will give you the option to show or hide the financial part on frontend.', 1, '', 0, 0,0],
            [17, 'Finance Imediate payment for Online Bookings', 'finance_imediate_payment_for_online_bookings', 'This setting will give you the option to have the payment of the online booking in 5 minutes after the booking is done, otherwise the booking is cancelled.', 1, '', 0, 0,0],
            [18, 'Global Website Show Finance on Frontend', 'globalWebsite_show_finance_on_frontend', 'This setting will give you the option to display or hide the \"Financial\" top menu on frontend', 1, '', 0, 0,0],
            [19, 'Finance Simple PayPal Payment Account', 'finance_simple_paypal_payment_account', 'Please fill here your email address that you use to login to your paypal account. All the payment using PayPal will go straight to your account defined by the email address used for PayPal registration.', 0, 'string', 6, 255,0],
            [20, 'Global Website Frontend About Text', 'globalWebsite_front_about_text', 'This is the text shown on frontend of your subdomain, un the footer area under the About text', 0, 'string', 30, 200,0],
            [21, 'Global Website Frontend Phone Number', 'globalWebsite_front_phone_number', 'This is the phone number shown on homepage in the footer are under contacts.', 0, 'string', 3, 25,0],
            [22, 'Global Website Frontend Contact Email', 'globalWebsite_front_contact_email', 'This is the contact email shown on homepage in the footer area under contacts.', 0, 'string', 6, 150,0],
            [23, 'Social Media Frontend Footer RSS', 'social_media_frontend_footer_rss', 'If a link is present here then this link will be shown on frontend footer part - RSS icon. Please use http/https when entering the link and test if after you set it up here.', 0, 'string', 5, 255,0],
            [24, 'Social Media Frontend Footer Facebook', 'social_media_frontend_footer_facebook', 'If a link is present here then this link will be shown on frontend footer part - Facebook icon. Please use http/https when entering the link and test if after you set it up here.', 0, 'string', 5, 255,0],
            [25, 'Social Media Frontend Footer Twitter', 'social_media_frontend_footer_twitter', 'If a link is present here then this link will be shown on frontend footer part - Twitter icon. Please use http/https when entering the link and test if after you set it up here.', 0, 'string', 5, 255,0],
            [26, 'Social Media Frontend Footer Google+', 'social_media_frontend_footer_google+', 'If a link is present here then this link will be shown on frontend footer part - Google+ icon. Please use http/https when entering the link and test if after you set it up here.', 0, 'string', 5, 255,0],
            [27, 'Social Media Frontend Footer LinkedIn', 'social_media_frontend_footer_linkedin', 'If a link is present here then this link will be shown on frontend footer part - LinkedIn icon. Please use http/https when entering the link and test if after you set it up here.', 0, 'string', 5, 255,0],
            [28, 'Social Media Frontend Footer YpuTube', 'social_media_frontend_footer_youtube', 'If a link is present here then this link will be shown on frontend footer part - YouTube icon. Please use http/https when entering the link and test if after you set it up here.', 0, 'string', 5, 255,0],
            [29, 'Social Media Frontend Footer Vimeo', 'social_media_frontend_footer_vimeo', 'If a link is present here then this link will be shown on frontend footer part - Vimeo icon. Please use http/https when entering the link and test if after you set it up here.', 0, 'string', 5, 255,0],
            [30, 'Global Website Registration Finished', 'globalWebsite_registration_finished', 'This variable will show if the registration is finished or not', 1, '', 0, 0,1],
            [31, 'Global Website Show Store Credit Packs on Front End', 'globalWebsite_show_store_credit_packs_on_frontend', 'This settign will show or hide the page from where store credit package can be bought.', 1, '', 0, 0, 0],
            [32, 'Finance Paypal Account Key', 'finance_paypal_account_key', 'Advanced PayPal integration - this is your account key', 0, 'string', 15, 60, 0],
            [33, 'Finance Paypal Account Secret', 'finance_paypal_account_secret', 'Advanced PayPal integration - account secret. This is paired with PayPal Key.', 0, 'string', 2, 60, 0],
            [34, 'Finance Stripe Account Key', 'finance_stripe_account_key', 'Stripe payment integration - Account key', 0, 'string', 5, 60, 0],
            [35, 'Finance Stripe Account Secret', 'finance_stripe_account_secret', 'Stripe payment integration - Account Secret', 0, 'string', 5, 60, 0],
            [36, 'Global Website Registration Email Validation', 'globalWebsite_registration_email_validation', 'If set to yes, all new member registration will require email verification : an email will be sent to them to activate their accounts.', 1, '', 0, 0, 0],
            [37, 'Global Website Custm Terms and Agreements', 'globalWebsite_custom_terms_and_agreements', 'In this field you can place the terms and agreements from your own website for buying memberships and other financial packages.', 0, 'string', 5, 200, 0],
            [38, 'Global Website Facebook FeedLink', 'globalWebsite_facebook_feedLink', 'Facebook account link for showing homepage Facebook embeded feed from facebook page', 0, 'string', 5, 220, 0],
            [39, 'Global Website Account Logo Image', 'globalWebsite_account_logo_image', 'This is the image that will be used as logo in all the application places', 0, 'string', 5, 255, 0],
            [40, 'Global Website Contact Gmaps Points', 'globalWebsite_contact_gmaps_points', 'This value holds the google maps points for the google maps available in the contact form part.', 0, 'text', 1, 9999, 0],
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
            [1, 5, 'Yes',   '1'],
            [2, 5, 'No',    '0'],
            [3, 6, 'Yes',   '1'],
            [4, 6, 'No',    '0'],
            [5, 11, 'Yes',  '1'],
            [6, 11, 'No',   '0'],
            [7, 12, 'Yes',  '1'],
            [8, 12, 'No',   '0'],
            [9, 13, 'Yes',  '1'],
            [10, 13, 'No',  '0'],
            [11, 15, 'Yes', '1'],
            [12, 15, 'No',  '0'],
            [13, 16, 'Yes', '1'],
            [14, 16, 'No',  '0'],
            [15, 17, 'Yes', '1'],
            [16, 17, 'No',  '0'],
            [17, 18, 'Yes', '1'],
            [18, 18, 'No',  '0'],
            [19, 30, 'Yes',  '1'],
            [20, 30, 'No',  '0'],
            [21, 31, 'Yes',  '1'],
            [22, 31, 'No',  '0'],
            [24, 36, 'Yes',  '1'],
            [25, 36, 'No',  '0'],
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
        DB::insert($query, [1,  NULL, env('URL'),   1]);
        DB::insert($query, [2,  NULL, '578',1]);
        DB::insert($query, [3,  NULL, env('MY_SERVER_URL'),     1]);
        DB::insert($query, [4,  NULL, 'booking_agent@book247.net', 1]);
        DB::insert($query, [6,  NULL, 'SQF',1]);
        DB::insert($query, [7,  NULL, 'NOK',1]);
        DB::insert($query, [8,  NULL, '6',  1]);
        DB::insert($query, [9,  NULL, '6',  1]);
        DB::insert($query, [10, NULL, '1',  1]);
        DB::insert($query, [14, NULL, '',   1]);
        DB::insert($query, [20, NULL, 'Er du interessert i å jobbe med squash og trening? Da kan det hende du passer til våre jobber.', 1]);
        DB::insert($query, [21, NULL, '22 20 70 60', 1]);
        DB::insert($query, [22, NULL, 'drammen@sqf.no', 1]);

        // confined values
        DB::insert($query, [5,  2,  NULL, 1]);
        DB::insert($query, [11, 6,  NULL, 1]);
        DB::insert($query, [12, 8,  NULL, 1]);
        DB::insert($query, [13, 9,  NULL, 1]);
        DB::insert($query, [15, 11, NULL, 1]);
        DB::insert($query, [16, 13, NULL, 1]);
        DB::insert($query, [17, 16, NULL, 1]);
        DB::insert($query, [18, 17, NULL, 1]);
        DB::insert($query, [30, 20, NULL, 1]);
        DB::insert($query, [31, 21, NULL, 1]);
        DB::insert($query, [36,  25,  NULL, 1]);
        DB::insert($query, [40,  NULL,  json_encode([]), 1]);


        DB::unprepared('ALTER TABLE settings AUTO_INCREMENT=10000;');
        DB::unprepared('ALTER TABLE allowed_setting_values AUTO_INCREMENT=10000;');
        DB::unprepared('ALTER TABLE application_settings AUTO_INCREMENT=10000;');

    }
}
