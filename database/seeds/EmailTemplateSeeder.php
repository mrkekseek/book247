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
        $emailValues = [
            ['Your booking for [[booking_date]] was canceled', 'Booking cancellation - multiple (recurring bookings)', '["booking_date","login_details","cancel_booking_details","first_name","middle_name","last_name","company_name","my_booking_link"]', 'The following bookings were canceled: [[cancel_booking_details]]<br><br>You can view this information in your account by accessing&nbsp;[[my_booking_link]] .<br><br>Sincerely,<br>Book247 Team. <br><br><small><strong>***** Email confidentiality notice *****</strong><br>This message is private and confidential. If you have received this message in error, please notify us and remove it from your system.</small>', 'Booking cancellation - multiple', '0', 578, '2017-07-05 09:57:00', '2017-08-14 07:04:08'],
            ['Your booking for [[booking_date]] was canceled', 'Booking cancellation - single', '["company_name","middle_name","last_name","booking_date","booking_details","cancel_booking_details","first_name","my_booking_link"]', 'The following booking was canceled: [[cancel_booking_details]] <br><br> You can view this information in your account by accessing&nbsp;[[my_booking_link]] <br><br>Sincerely,<br>Book247 Team. <br><br><small><strong>***** Email confidentiality notice *****</strong><br>This message is private and confidential. If you have received this message in error, please notify us and remove it from your system.</small>', 'Booking cancellation - single', '0', 578, '2017-07-05 09:57:00', '2017-08-14 06:58:33'],
            ['Several bookings were created', 'Booking confirmation – multiple', '["first_name","last_name","middle_name","company_name","booking_date","login_details","booking_details","my_booking_link"]', 'Several bookings were created: [[booking_details]] <br><br> You can view this information in your account by accessing [[my_booking_link]] <br><br>Sincerely,<br>Book247 Team. <br><br><small><strong>***** Email confidentiality notice *****</strong><br>This message is private and confidential. If you have received this message in error, please notify us and remove it from your system.</small>', 'Booking confirmation – multiple', '0', 578, '2017-07-05 09:57:00', '2017-08-14 06:58:52'],
            ['Your booking for [[booking_date]] was created', 'Booking Confirmation – single', '["middle_name","last_name","company_name","booking_date","booking_details","first_name","my_booking_link"]', 'This is your booking confirmation for the following date and time: [[booking_details]] <br><br> You can view this information in your account by accessing [[my_booking_link]] <br><br>Sincerely,<br>Book247 Team. <br><br><small><strong>***** Email confidentiality notice *****</strong><br>This message is private and confidential. If you have received this message in error, please notify us and remove it from your system.</small>', 'Booking Confirmation – single', '0', 578, '2017-07-05 09:57:00', '2017-08-14 07:02:58'],

            ['You got a new friend', 'Add friend by phone number in frontend – no approval needed', '["first_name","middle_name","last_name","company_name","friends_list_link"]', 'You have a new friend request - [[first_name]] [[middle_name]] [[last_name]] - that was automatically approved.<br><br>To manage your friends and friends approval option, go to your [[friends_list_link]]. You can remove the ones you don\'t want by clicking <strong>Remove</strong> button. <br><br>Sincerely,<br>Book247 Team. <br><br><small><strong>***** Email confidentiality notice *****</strong><br>This message is private and confidential. If you have received this message in error, please notify us and remove it from your system.</small>', 'Add friend by phone number in frontend – no approval needed', '0', 578, '2017-08-14 08:33:39', '2017-08-25 09:52:54'],
            ['You got a new friend request that needs approval', 'Add friend by phone number in frontend – approval needed', '["first_name","middle_name","last_name","company_name","friends_list_link"]', 'You have a new friend request - <strong>[[first_name]] [[middle_name]] [[last_name]]</strong> - that is waiting your approval. If you know this person you can accept the request, otherwise decide to accept or reject it.<br><br>To manage your friends and friends approval option, go to your [[friends_list_link]]. You can remove the ones you don\'t want by clicking <strong>Remove</strong> button. <br><br>Sincerely,<br>Book247 Team. <br><br><small><strong>***** Email confidentiality notice *****</strong><br>This message is private and confidential. If you have received this message in error, please notify us and remove it from your system.</small>', 'Add friend by phone number in frontend – approval needed', '0', 578, '2017-08-14 08:33:39', '2017-08-25 10:00:40'],

            ['Online Booking System - You are registered!', 'New front member registration no validation', '["first_name","middle_name","last_name","company_name","login_details","member_login_link","username","password","mobil_number"]', 'You are now registered in Book 24/7 which is the official booking system for [[company_name]]. <br> You can use the [[member_login_link]] to log in with the following credentials:<br> Username: [[username]]<br> Password: [[password]] <br><br> Your phone : <strong>[[phone_number]]</strong> that is registered in the system can be used to send you alerts when you create a booking or when a booking is created on your behalf. <br><br>Sincerely,<br>Book247 Team. <br><br><small><strong>***** Email confidentiality notice *****</strong><br>This message is private and confidential. If you have received this message in error, please notify us and remove it from your system.</small>', 'New front member registration', '0', 578, '2017-07-05 09:57:00', '2017-08-14 07:05:34'],
            ['Password reset request', 'Password reset – first step after reset password request', '["first_name","middle_name","company_name","last_name","reset_password_link"]', 'This is a password reset request email sent by Booking System Agent. If you did not request a password reset, ignore this email.<br><br>If this request was initiated by you, click the following link to [[reset_password_link]]. The link will be available for the next 60 minutes, after that you will need to request another password reset request.<br><br>Once the password is reset you will get a new email with the outcome of your action, then you can login to the system with your newly created password. <b>Remember this link is active for the next 60 minutes.</b><br><br>Sincerely,<br>Book247 Team. <br><br><small><strong>***** Email confidentiality notice *****</strong><br>This message is private and confidential. If you have received this message in error, please notify us and remove it from your system.</small>', 'Password reset – first step after reset password ', '0', 578, '2017-07-05 09:57:00', '2017-08-14 07:05:48'],
            ['Password successfully changed', 'Password reset – second step after reset link accessed', '["first_name","middle_name","last_name","company_name"]', 'You have successfully updated your password using the reset password link we sent. Now you can login using your new password. If this was not done by you, please contact the Booking System administrator and report this situation. <br><br>Sincerely,<br>Book247 Team. <br><br><small><strong>***** Email confidentiality notice *****</strong><br>This message is private and confidential. If you have received this message in error, please notify us and remove it from your system.</small>', 'Password reset – second step after reset link accessed', '0', 578, '2017-07-05 09:57:00', '2017-08-14 06:22:57'],
            ['[[company_name]] - Your friend has booked on your behalf', 'booking confirmation when a friend has book on behalf of a friend', '["first_name","middle_name","last_name","company_name","booking_details","my_bookings_link","friends_first_name","friends_last_name"]', 'Your friend&nbsp;[[friends_first_name]]&nbsp;[[friends_last_name]] has made the following booking on your behalf: <br> [[booking_details]] <br><br> You can view and edit your bookings at&nbsp;[[my_bookings_link]]. <br><br>Sincerely,<br>Book247 Team. <br><br><small><strong>***** Email confidentiality notice *****</strong><br>This message is private and confidential. If you have received this message in error, please notify us and remove it from your system.</small>', 'booking confirmation when a friend has book on behalf of a friend', '0', 578, '2017-07-05 09:57:00', '2017-08-11 12:55:25'],
            ['[[company_name]] - Your court has been canceled', 'Cancellation email when completed by friend', '["first_name","last_name","middle_name","company_name","my_bookings_link","booking_details","friend_first_name","friend_last_name"]', 'Your friend&nbsp;[[friend_first_name]]&nbsp;[[friend_last_name]] has canceled a booking on your behalf:&nbsp; <br> [[booking_details]] <br><br>Sincerely,<br>Book247 Team. <br><br><small><strong>***** Email confidentiality notice *****</strong><br>This message is private and confidential. If you have received this message in error, please notify us and remove it from your system.</small>', 'Cancellation email when completed by friend', '0', 578, '2017-07-05 09:57:00', '2017-08-11 12:57:01'],
            ['One step away from registration', 'This email is sent to new front member registration when account verification by email is set to yes', '["first_name","last_name","middle_name","activation_link","username","password","company_name","phone_number","member_login_link"]', '<p>Book247 is the official booking system for [[company_name]]. Thank you for registering to our online booking system. To finish the registration process, pleace click the following link to  [[activation_link]].<br><br> After your account is active, you can <strong>[[member_login_link]]</strong> using these credentials: <br> Username :  [[username]]<br> Password :  [[password]]<br><br>Your phone : <strong>[[phone_number]]</strong> that is registered in the system can be used to send you alerts when you create a booking or when a booking is created on your behalf.<br><br>Sincerely,<br>Book247 Team. <br><br><small><strong>***** Email confidentiality notice *****</strong><br>This message is private and confidential. If you have received this message in error, please notify us and remove it from your system.</small></p>', 'New front member registration with validation', '0', 578, '2017-08-13 09:39:19', '2017-08-13 12:00:54'],
            ['Activate your Book247 account', 'This email is sent to new front member registration when account verification by email is set to yes and user requested the account validation once more', '["first_name","last_name","middle_name","activation_link","username","password","company_name","phone_number","member_login_link"]', '<p>Book247 is the official booking system for [[company_name]]. Thank you for registering to our online booking system. To finish the registration process, pleace click the following link to&nbsp; [[activation_link]].<br><br> After your account is active, you can <strong>[[member_login_link]]</strong> using these credentials: <br> Username :  [[username]]<br> Password : <i>the one used at registration</i> <br><br>Your phone : <strong>[[phone_number]]</strong> that is registered in the system can be used to send you alerts when you create a booking or when a booking is created on your behalf.<br><br>Sincerely,<br>Book247 Team. <br><br><small><strong>***** Email confidentiality notice *****</strong><br>This message is private and confidential. If you have received this message in error, please notify us and remove it from your system.</small></p>', 'Resend front member registration with validation', '0', 578, '2017-08-13 09:39:19', '2017-08-13 20:07:25'],
            ['Registration successful!', 'Registering - existing owner', '["first_name","last_name","email","club_name","password"]', 'Registration successful existing owner', 'Registering - existing owner', '0', 578, '2017-07-05 09:57:00', '2017-08-11 12:55:25'],
            ['Registration successful!', 'Registration - new owner', '["first_name","last_name","email","club_name","password"]', 'Registration successful new owner', 'Registration - new owner', '0', 578, '2017-07-05 09:57:00', '2017-08-11 12:55:25'],
            ['Hello [[first_name]] [[last_name]]. Your account was created.', 'Your account was successfully created.', '["first_name","last_name","email","middle_name","password","email","username","role"]', 'Your account was successfully created. You can log in using your email and your password.<br/>First Name: <b>[[first_name]]</b><br/>Middle Name: <b>[[middle_name]]</b><br/>Last Name: <b>[[last_name]]</b><br/>Username: <b>[[username]]</b><br/>Email: <b>[[email]]</b><br/>Password: <b>[[password]]</b><br/>Role: <b>[[role]]</b><br/>', 'New back end user registration', '0', 578, '2017-07-05 09:57:00', '2017-08-11 12:55:25'],

        ];

        DB::table('email_templates')->truncate();

        foreach ($emailValues as $single)
        {
            DB::table('email_templates')->insert([
				'title'         => $single[0],
				'content'       => $single[1],
				'variables'     => $single[2],
				'description'   => $single[3],
				'hook'          => $single[4],
				'is_default'    => $single[5],
				'country_id'    => $single[6],
				'created_at'    => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'    => Carbon::now()->format('Y-m-d H:i:s')
			]);
        }
    }
}
