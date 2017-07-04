<?php

namespace App\Http\Controllers\Federation;

use App\Http\Controllers\AppSettings as Base;
use Illuminate\Http\Request;
use App\Settings;
use DB;
use Auth;

class AppSettings extends  Base{

    public function index()
    {
        $user = Auth::user();
        if ( ! $user || ! $user->is_back_user())
        {
            return redirect()->intended(route('admin/login'));
        }

        $allSettings = Settings::get_formatted();

        $breadcrumbs = [
            'Home'              => route('admin'),
            'Administration'    => route('admin'),
            'Settings'          => '',
        ];
        $text_parts  = [
            'title'     => 'General Settings Define Page',
            'subtitle'  => 'list all',
            'table_head_text1' => 'All Settings List'
        ];
        $sidebar_link= 'admin-settings-all_list';

        return view('admin/settings/federation/all_settings_list', [
            'breadcrumbs' => $breadcrumbs,
            'text_parts'  => $text_parts,
            'in_sidebar'  => $sidebar_link,
            'settings'    => Settings::all(),
            'data_types'  => array("string" => "String / Alphanumeric Values", "text" => "Text", "numeric" => "Numeric Only", "date" => "Date / DateTime Value")
        ]);
    }


    public function manage_settings(Request $request)
    {
        $user = Auth::user();
        if ( ! $user || ! $user->is_back_user())
        {
            return redirect()->intended(route('admin/login'));
        }

        $allowed = array();
        foreach (DB::table("allowed_setting_values")->get() as $row)
        {
            $allowed[$row->setting_id][] = $row;
        }

        $app_settings = array();
        foreach (DB::table("application_settings")->get() as $row)
        {
            $app_settings[$row->setting_id] = $row->unconstrained_value != '' ? $row->unconstrained_value : $row->allowed_setting_value_id;
        }

        $settings = array();
        foreach (Settings::all() as $row)
        {
            $row['value'] = isset($app_settings[$row->id]) ? $app_settings[$row->id] : "";
            $row['allowed'] = isset($allowed[$row->id]) ? $allowed[$row->id] : FALSE;
            $settings[] = $row;
        }

        $breadcrumbs = [
            'Home'              => route('admin'),
            'Administration'    => route('admin'),
            'Settings'          => '',
        ];
        $text_parts  = [
            'title'     => 'Manage settings',
            'subtitle'  => 'list all',
            'table_head_text1' => ''
        ];
        $sidebar_link = 'admin-settings-manage_settings';

        return view('admin/settings/federation/manage_settings', [
            'breadcrumbs' => $breadcrumbs,
            'text_parts'  => $text_parts,
            'in_sidebar'  => $sidebar_link,
            'settings'    => $settings,
            'data_types'  => array("string" => "String / Alphanumeric Values", "text" => "Text", "numeric" => "Numeric Only", "date" => "Date / DateTime Value"),
            'allowed'     => $allowed
        ]);
    }

    public function rankedin_app_key_integration(){
        $user = Auth::user();
        if ( ! $user || ! $user->is_back_user()) {
            return redirect()->intended(route('admin/login'));
        }

        $app_key = $this::get_setting_value_by_name('globalWebsite_rankedin_integration_key');

        $breadcrumbs = [
            'Home'              => route('admin'),
            'Administration'    => route('admin'),
            'Settings'          => '',
        ];
        $text_parts  = [
            'title'     => 'RankedIn Integration - Account Key',
            'subtitle'  => 'your unique identifier',
            'table_head_text1' => ''
        ];
        $sidebar_link = 'admin-settings-rankedin_integration_app_key';

        return view('admin/settings/federation/rankedin_integration', [
            'breadcrumbs' => $breadcrumbs,
            'text_parts'  => $text_parts,
            'in_sidebar'  => $sidebar_link,
            'application_key'   => $app_key
        ]);
    }

}