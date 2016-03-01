<?php

use Illuminate\Database\Seeder;

class VatRatesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('vat_rates')->insert([
            'name'          => '24percent',
            'display_name'  => '24% All Products',
            'value'         => '24',
            'description'   => 'This is the general VAT rule in NO',
        ]);

        DB::table('vat_rates')->insert([
            'name'          => '20percent',
            'display_name'  => '20% All Products',
            'value'         => '20',
            'description'   => 'This is the 2016 VAT rule in NO',
        ]);

        DB::table('vat_rates')->insert([
            'name'          => '9percent',
            'display_name'  => '9% All Products',
            'value'         => '9',
            'description'   => 'This is the food vat rule',
        ]);

        DB::table('vat_rates')->insert([
            'name'          => '0percent',
            'display_name'  => '0% Old Products',
            'value'         => '0',
            'description'   => 'This is the general VAT rule in NO',
        ]);
    }
}
