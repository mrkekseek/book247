<?php

use Illuminate\Database\Seeder;

class ShopResourceCategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('shop_resource_categories')->insert([
            'name'  => 'Tennis',
            'url'   => 'tennis',
        ]);
        DB::table('shop_resource_categories')->insert([
            'name'  => 'Squash',
            'url'   => 'squash',
        ]);
        DB::table('shop_resource_categories')->insert([
            'name'  => 'Golf',
            'url'   => 'golf',
        ]);
    }
}
