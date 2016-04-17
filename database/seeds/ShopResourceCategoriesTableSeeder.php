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
            'name'  => 'Squash Court XS',
            'url'   => 'squash-court-xs',
        ]);
        DB::table('shop_resource_categories')->insert([
            'name'  => 'Squash Court S',
            'url'   => 'squash-court-s',
        ]);
        DB::table('shop_resource_categories')->insert([
            'name'  => 'Squash Court M',
            'url'   => 'squash-court-m',
        ]);
        DB::table('shop_resource_categories')->insert([
            'name'  => 'Squash Court L',
            'url'   => 'squash-court-l',
        ]);
    }
}
