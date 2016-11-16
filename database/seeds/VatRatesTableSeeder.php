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
            'name'          => '25percent',
            'display_name'  => '25% Standard',
            'value'         => '25',
            'description'   => 'standard VAT rate in Norway',
        ]);

        DB::table('vat_rates')->insert([
            'name'          => '15percent',
            'display_name'  => '15% Reduced',
            'value'         => '15',
            'description'   => 'reduced VAT rate on: foodstuffs and beverages',
        ]);

        DB::table('vat_rates')->insert([
            'name'          => '11.11percent',
            'display_name'  => '11.11% Reduced',
            'value'         => '11.11',
            'description'   => 'reduced VAT rate on: supply of fish',
        ]);

        DB::table('vat_rates')->insert([
            'name'          => '10percent',
            'display_name'  => '10% Reduced',
            'value'         => '10',
            'description'   => 'reduced VAT rate on: certain cultural and sporting activities',
        ]);

        DB::table('vat_rates')->insert([
            'name'          => '0percent',
            'display_name'  => '0% No VAT',
            'value'         => '0',
            'description'   => 'no VAT rate for products',
        ]);
    }
}
