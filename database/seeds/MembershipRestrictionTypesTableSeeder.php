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
            'created_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
        DB::table('membership_restriction_types')->insert([
            'name' => 'open_bookings',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
        DB::table('membership_restriction_types')->insert([
            'name' => 'cancellation',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
        DB::table('membership_restriction_types')->insert([
            'name' => 'price',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
        DB::table('membership_restriction_types')->insert([
            'name' => 'included_activity',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
        DB::table('membership_restriction_types')->insert([
            'name' => 'booking_time',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
    }
}
