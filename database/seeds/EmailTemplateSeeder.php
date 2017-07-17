<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class EmailTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $settingsInsertValues = [
        	[
        		'title' => '[[company_name]] - Your booking for [[booking_date]] was canceled',
        		'content'      => 'Booking cancellation - multiple (recurring bookings)',
        		'variables'      => '["booking_date","login_details","cancel_booking_details","first_name","middle_name","last_name","company_name","my_booking_link"]',
        		'description'      => '<p>Hi [[first_name]] [[middle_name]] [[last_name]],</p><p>The following bookings were canceled:</p><p>[[cancel_booking_details]]</p><p>You can view this information in your account by accessing&nbsp;[[my_booking_link]]<br></p>',
        		'hook'      => 'Booking cancellation - multiple',
        		'is_default'      => 0,
        		'country_id'      => 578,
        		'created_at'      => Carbon::now()->format('Y-m-d H:i:s'),
        	],
        	[
        		'title' => '[[company_name]] - Your booking for [[booking_date]] was canceled',
        		'content'      => 'Booking cancellation - single',
        		'variables'      => '["company_name","middle_name","last_name","booking_date","booking_details","cancel_booking_details","first_name","my_booking_link"]',
        		'description'      => '<p>Hi [[first_name]] [[middle-name]]&nbsp;[[last-name]],</p><p>The following booking was canceled:</p><p>[[cancel_booking_details]]</p><p>You can view this information in your account by accessing&nbsp;[[my_booking_link]]</p>',
        		'hook'      => 'Booking cancellation - single',
        		'is_default'      => 0,
        		'country_id'      => 578,
        		'created_at'      => Carbon::now()->format('Y-m-d H:i:s'),
        	],
        	[
        		'title' => '[[company_name]] - Several bookings were created',
        		'content'      => 'Booking confirmation – multiple',
        		'variables'      => '["first_name","last_name","middle_name","company_name","booking_date","login_details","booking_details","my_booking_link"]',
        		'description'      => '<p>Hi&nbsp;[[first_name]]&nbsp;[[middle_name]]&nbsp;[[last_name]],</p><p>Several bookings were created:</p><p>[[booking_details]]</p><p>You can view this information in your account by accessing&nbsp;[[my_booking_link]]<br></p>',
        		'hook' => 'Booking confirmation – multiple',
        		'is_default'      => 0,
        		'country_id'      => 578,
        		'created_at'      => Carbon::now()->format('Y-m-d H:i:s'),
        	],
        	[
        		'title' => '[[company_name]] - Your booking for [[booking_date]] was created',
        		'content'      => 'Booking Confirmation – single',
        		'variables'      => '["middle_name","last_name","company_name","booking_date","booking_details","first_name","my_booking_link"]',
        		'description'      => '<p>Hi [[first_name]]&nbsp;[[middle_name]]&nbsp;[[last_name]],</p><p>This is your booking confirmation for the following date and time:</p><p>[[booking_details]]</p><p>You can view this information in your account by accessing&nbsp;[[my_booking_link]]<br></p>',
        		'hook' => 'Booking Confirmation – single',
        		'is_default'      => 0,
        		'country_id'      => 578,
        		'created_at'      => Carbon::now()->format('Y-m-d H:i:s'),
        	],
        	[
        		'title' => '[[company_name]] - You got a new friend',
        		'content'      => 'Add friend by phone number in frontend – no approval needed',
        		'variables'      => '["first_name","middle_name","last_name","company_name","friend\u00b4s_list_link"]',
        		'description'      => '<p>Hi&nbsp;[[first_name]]&nbsp;[[middle_name]]&nbsp;[[last_name]],</p><p>You have a new friend - [[first_name]] [[middle_name]] [[last_name]]
</p><p>To manage your friends, go to your [[friend´s_list_link]].&nbsp;You can
remove the ones you don´t want by clicking <strong>Remove</strong> button.&nbsp;<br></p>',
        		'hook'      => 'Add friend by phone number in frontend – no approval needed',
        		'is_default'      => 0,
        		'country_id'      => 578,
        		'created_at'      => Carbon::now()->format('Y-m-d H:i:s'),
        	],
        	[
        		'title' => '[[company_name]] - You got a new friend request that needs approval',
        		'content'      => 'Add friend by phone number in frontend – approval needed',
        		'variables'      => '["first_name","middle_name","last_name","company_name","friend\u00b4s_list_link"]',
        		'description'      => '<p>Hi&nbsp;[[first_name]]&nbsp;[[middle_name]]&nbsp;[[last_name]],</p><p>You have a new friend request - [[first_name]] [[middle_name]] [[last_name]]</p><p>To manage your friends, go to your [[friend´s_list_link]]&nbsp;.&nbsp;You can remove the ones you don´t want by clicking&nbsp;<span style="font-weight: 700;">Remove</span>&nbsp;button.&nbsp;</p>',
        		'hook'      => 'Add friend by phone number in frontend – approval needed',
        		'is_default'      => 0,
        		'country_id'      => 578,
        		'created_at'      => Carbon::now()->format('Y-m-d H:i:s'),
        	],
        	[
        		'title' => '[[company_name]] - Online Booking System - You are registered!',
        		'content'      => 'New front member registration',
        		'variables'      => '["first_name","middle_name","last_name","company_name","login_details","member_login_link","username","password","mobil_number"]',
        		'description'      => '<p>Hi&nbsp;[[first_name]]&nbsp;[[middle_name]]&nbsp;[[last_name]],</p><p>You are now registered in Book 24/7 which is the official booking system for&nbsp;[[company_name]].</p><p>You can use the&nbsp;[[member_login_link]] to log in with the following credentials:</p><p>Username: [[username]]</p><p>Password: [[password]]</p><p><br></p><p><br></p>',
        		'hook'      => 'New front member registration',
        		'is_default'      => 0,
        		'country_id'      => 578,
        		'created_at'      => Carbon::now()->format('Y-m-d H:i:s'),
        	],
        	[
        		'title' => '[[company_name]] - Password reset request',
        		'content'      => 'Password reset – first step after reset password request',
        		'variables'      => '["first_name","middle_name","company_name","last_name","reset_password_link"]',
        		'description'      => '<p>Hi&nbsp;[[first_name]]&nbsp;[[middle_name]]&nbsp;[[last_name]],</p><p>This is a password reset request email sent by Booking System Agent. If you did not request a password</p><p>reset, ignore this email.</p><p>If this request was initiated by you, click the following link to [[reset_password_link]].</p><p>The link will be available for the next 60 minutes, after that you will need to request another password</p><p>reset request.</p><p>Once the password is reset you will get a new email with the outcome of your action, then you can login</p><p>to the system with your newly created password.</p><p>&lt;b&gt;Remember this link is active for the next 60 minutes.&lt;/b</p>',
        		'hook'      => 'Password reset – first step after reset password ',
        		'is_default'      => 0,
        		'country_id'      => 578,
        		'created_at'      => Carbon::now()->format('Y-m-d H:i:s'),
        	],
        	[
        		'title' => '[[company_name]] - Password successfully changed',
        		'content'      => 'Password reset – second step after reset link accessed',
        		'variables'      => '["first_name","middle_name","last_name","company_name"]',
        		'description'      => '<p>Hi&nbsp;[[first_name]]&nbsp;[[middle_name]]&nbsp;[[last_name]],</p><p>You have successfully updated your password using the reset password link we sent. Now you can login
using your new password.
If this was not done by you, please contact the Booking System administrator and report this issue.<br></p>',
        		'hook'      => 'Password reset – second step after reset link accessed',
        		'is_default'      => 0,
        		'country_id'      => 578,
        		'created_at'      => Carbon::now()->format('Y-m-d H:i:s'),
        	],
        	[
        		'title' => '[[company_name]] - Your friend has booked on your behalf',
        		'content'      => 'booking confirmation when a friend has book on behalf of a friend',
        		'variables'      => '["first_name","middle_name","last_name","company_name","booking_details","my_bookings_link","friend\u00b4s_first_name","friend\u00b4s_last_name"]',
        		'description'      => '<p>Hi&nbsp;[[first_name]]&nbsp;[[middle_name]]&nbsp;[[last_name]],</p><p>Your friend&nbsp;[[friend´s_first_name]]&nbsp;[[friend´s_last_name]] has made the following booking on your behalf:</p><p>[[booking_details]]</p><p>You can view and edit your bookings at&nbsp;[[my_bookings_link]]</p><p><br></p>',
        		'hook'      => 'booking confirmation when a friend has book on behalf of a friend',
        		'is_default'      => 0,
        		'country_id'      => 578,
        		'created_at'      => Carbon::now()->format('Y-m-d H:i:s'),
        	],
        	[
        		'title' => '[[company_name]] - Your court has been canceled',
        		'content'      => 'Cancellation email when completed by friend',
        		'variables'      => '["first_name","last_name","middle_name","company_name","my_bookings_link","booking_details","friend\u00b4s_first_name","friend\u00b4s_last_name"]',
        		'description'      => '<p>Hi&nbsp;[[first_name]]&nbsp;[[middle_name]]&nbsp;[[last_name]],</p><p>Your friend&nbsp;[[friend´s_first_name]]&nbsp;[[friend´s_last_name]] has canceled a booking on your behalf:&nbsp;</p><p>[[booking_details]]&nbsp;<br></p>',
        		'hook'      => 'Cancellation email when completed by friend',
        		'is_default'      => 0,
        		'country_id'      => 578,
        		'created_at'      => Carbon::now()->format('Y-m-d H:i:s'),
        	],
            [
                'title' => 'Hello [[first_name]] [[last_name]]!',
                'content'      => 'Template when registering a new owner',
                'variables'      => '["club_name","email","password","first_name","last_name"]',
                'description'      => "<p><span>You have succesfully registered at book247.net.</span><br>Your club's name is [[club_name]].<br><span>Now you have your own subdomain for your club:&nbsp;</span>[[club_name]]<span>.book247.net</span><br><span>Your data for login:</span><br>Username: [[email]]<br>Password&nbsp;[[password]]<br></p><p><span>Sincerely,&nbsp;</span><span>Team book247!</span></p>",
                'hook'      => 'Registration of new owner',
                'is_default'      => 0,
                'country_id'      => 578,
                'created_at'      => Carbon::now()->format('Y-m-d H:i:s'),
            ],
            [
                'title' => 'Hello [[first_name]] [[last_name]]!',
                'content'      => 'Template when registering an existing owner',
                'variables'      => '["club_name","email","first_name","last_name"]',
                'description'      => "<p><span>You have succesfully registered at book247.net.</span><br>Your club's name is [[club_name]].<br><span>Now you have your own subdomain for your club:&nbsp;</span>[[club_name]]<span>.book247.net</span><br><span>Your data for login:</span><br>Username: [[email]]<br>Password: use password you have created before.<br></p><p><span>Sincerely,&nbsp;</span><span>Team book247!</span></p>",
                'hook'      => 'Registering an existing owner',
                'is_default'      => 0,
                'country_id'      => 578,
                'created_at'      => Carbon::now()->format('Y-m-d H:i:s'),
            ]

        ];

        DB::table('email_templates')->truncate();

        foreach ($settingsInsertValues as $single)
        {
            DB::table('email_templates')->insert([
				'title' => $single['title'],
				'content' => $single['content'],
				'variables' => $single['variables'],
				'description' => $single['description'],
				'hook' => $single['hook'],
				'is_default' => $single['is_default'],
				'country_id' => $single['country_id'],
				'created_at' => $single['created_at']
			]);
        }
    }
}
