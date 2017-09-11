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
        if (!DB::table('shop_resource_categories')->where('name','Tennis')->where('url','tennis')->get()) {
            DB::table('shop_resource_categories')->insert([
                'name'  => 'Tennis',
                'url'   => 'tennis',
            ]);
        }

        if (!DB::table('shop_resource_categories')->where('name','Squash')->where('url','squash')->get()) {
            DB::table('shop_resource_categories')->insert([
                'name'  => 'Squash',
                'url'   => 'squash',
            ]);
        }

        if (!DB::table('shop_resource_categories')->where('name','Golf')->where('url','golf')->get()) {
            DB::table('shop_resource_categories')->insert([
                'name'  => 'Golf',
                'url'   => 'golf',
            ]);
        }


    }
}
