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
        $this->command->info('Seeded : Member Restrictions Table');

        $this->call('CountriesSeeder');
        $this->command->info('Seeded : Countries Table!');

        $this->call(VatRatesTableSeeder::class);
        $this->command->info('Seeded : VAT rates added to VAT Table!');

        $this->call(ShopResourceCategoriesTableSeeder::class);
        $this->command->info('Seeded : Shop Resource category Table');

        $this->call(DiscountTypeSeeder::class);
        $this->command->info('Seeded : Discounts type Table');

        $this->call(PermissionsTableSeeder::class);
        $this->command->info('Seeded : Permissions Table');

        $this->call(RolesTableSeeder::class);
        $this->command->info('Seeded : Roles Table');

        $this->call(UserTableSeeder::class);
        $this->command->info('Seeded : User admin + role');
    }
}
