<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call('MembershipRestrictionTypesTableSeeder');
        $this->command->info('Seeded the countries!');

        $this->call('CountriesSeeder');
        $this->command->info('Seeded the countries!');

        $this->call(VatRatesTableSeeder::class);
        $this->command->info('VAT added to DB!');

        $this->call(ShopResourceCategoriesTableSeeder::class);
        $this->command->info('Shop Resource category seeded');

        $this->call(DiscountTypeSeeder::class);
        $this->command->info('Seeded the users with random data!');

        $this->call(PermissionsTableSeeder::class);
        $this->command->info('Seeded the users with random data!');

        $this->call(UserTableSeeder::class);
        $this->command->info('Seeded the users with random data!');
    }
}
