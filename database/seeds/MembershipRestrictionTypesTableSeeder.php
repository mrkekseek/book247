<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class MembershipRestrictionTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('membership_restriction_types')->insert([
            'name' => 'time_of_day',
            'title' => 'Time of Day',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
        DB::table('membership_restriction_types')->insert([
            'name' => 'open_bookings',
            'title' => 'Nr. of Open Bookings',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
        DB::table('membership_restriction_types')->insert([
            'name' => 'cancellation',
            'title' => 'Cancellation Before',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
        DB::table('membership_restriction_types')->insert([
            'name' => 'price',
            'title' => 'Price',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
        DB::table('membership_restriction_types')->insert([
            'name' => 'included_activity',
            'title' => 'Included Activities',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
        DB::table('membership_restriction_types')->insert([
            'name' => 'booking_time_interval',
            'title' => 'Booking Time Interval',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
    }
}
