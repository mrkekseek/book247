<?php

use Illuminate\Database\Seeder;
use App\SettingsGroup;

class SettingsGroupsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $groups = new SettingsGroup;
        $groups->name = "website_general";
        $groups->save();

		$groups = new SettingsGroup;
        $groups->name = "finance";
        $groups->save();

        $groups = new SettingsGroup;
        $groups->name = "bookings";
        $groups->save();

		$groups = new SettingsGroup;
        $groups->name = "social media";
        $groups->save();
    }
}
