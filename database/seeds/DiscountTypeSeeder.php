<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DiscountTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('discount_types')->insert([
            'name'          => 'fix_amount',
            'other_details' => '',
            'created_at'    => Carbon::now()->format('Y-m-d H:i:s')
        ]);

        DB::table('discount_types')->insert([
            'name'          => 'percentage',
            'other_details' => '',
            'created_at'    => Carbon::now()->format('Y-m-d H:i:s')
        ]);
    }
}
