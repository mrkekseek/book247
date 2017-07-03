<?php

namespace App\Http\Controllers;

use App\allowedSettingValue;
use App\applicationSetting;
use App\Settings;
use Carbon\Carbon;
use Illuminate\Http\Request;

use DB;
use App\Http\Requests;
use Auth;
use Illuminate\Support\Facades\Validator;
use Cache;

class AppSettings extends Controller
{
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

        return view('admin/settings/all_settings_list', [
            'breadcrumbs' => $breadcrumbs,
            'text_parts'  => $text_parts,
            'in_sidebar'  => $sidebar_link,
            'settings'    => Settings::all(),
            'data_types'  => array("string" => "String / Alphanumeric Values", "text" => "Text", "numeric" => "Numeric Only", "date" => "Date / DateTime Value")
        ]);
    }

    public function register_new_setting(Request $request)
    {
        $user = Auth::user();
        if ( ! $user || ! $user->is_back_user())
        {
            return [
                'success'   => false,
                'title'     => 'Login Error',
                'errors'    => 'You need to be logged in to access this function'
            ];
        }

        $vars = $request->only('contained', 'data_type', 'description', 'max_value', 'min_value', 'name', 'system_internal_name');
        $fillable = [
            'name'                  => $vars['name'],
            'system_internal_name'  => $vars['system_internal_name'],
            'description'           => $vars['description'],
            'constrained'           => $vars['contained'] == "true" ? 1 : 0,
            'data_type'             => $vars['data_type'] ? $vars['data_type'] : "",
            'min_value'             => $vars['min_value'] * 1 ? $vars['min_value'] : 0,
            'max_value'             => $vars['max_value'] * 1 ? $vars['max_value'] : 0
        ];

        if ($fillable["constrained"])
        {
            $fillable["data_type"] = "";
            $fillable["min_value"] = 0;
            $fillable["max_value"] = 0;
        }

        $settingValidator = Validator::make($fillable, Settings::rules('POST'), Settings::$validationMessages, Settings::$attributeNames);
        if ($settingValidator->fails())
        {
            return array(
                'success'   => false,
                'title'     => 'Error Shop Details',
                'errors'    => $settingValidator->getMessageBag()->toArray()
            );
        }
        else{
            $newSetting = new Settings();
            $newSetting->fill($fillable);
            $newSetting->save();

            return [
                'success'   => true,
                'title'     => 'New Setting Variable Created',
                'message'   => 'A new variable was created and can be used in the system. Remember to give it a value before using it'
            ];
        }
        /** Stop - Add shop location to database */
    }

    public function save_setting_application(Request $request)
    {
        $user = Auth::user();
        if ( ! $user || ! $user->is_back_user())
        {
            return redirect()->intended(route('admin/login'));
        }

        $fillable = ["setting_id"               => $request->input("setting_id"),
                    "unconstrained_value"       => strlen($request->input("value"))>0    ? $request->input("value"):NULL,
                    "allowed_setting_value_id"  => strlen( $request->input("caption"))>0 ? $request->input("caption"):NULL,
                    "updated_by_id"             => $user->id];

        $setting = applicationSetting::with('setting')->where("setting_id", $request->input("setting_id"))->first();
        if ( ! $setting) {
            applicationSetting::create($fillable);

            $success_title = 'Application setting saved';
            $success_message = 'Saved value will be used in the system';
        }
        else {
            $setting->update($fillable);
            Cache::forget($setting->setting->system_internal_name);

            $success_title = 'Application setting updated';
            $success_message = 'Updated value will be used in the system';
        }

        return [
            'success'   => true,
            'title'     => $success_title,
            'message'   => $success_message
        ];
    }

    public function save_allowed_setting(Request $request)
    {
        $user = Auth::user();
        if ( ! $user || ! $user->is_back_user())
        {
            return redirect()->intended(route('admin/login'));
        }

        $fillable = array(
                "setting_id" => $request->input("setting_id"),
                "allowed_setting_value_id" => $request->input("allowed_id"),
                "updated_by_id" => Auth::user()->id
            );

        $setting = DB::table("application_settings")->where("setting_id", $request->input("setting_id"));

        if ( ! $setting->count())
        {
            DB::table("application_settings")->insert($fillable);
        }
        else
        {
            DB::table("application_settings")->where("id", $setting->first()->id)->update($fillable);
        }

        return [
            'success'   => true,
            'title'     => 'Setting Variable Created',
            'message'   => 'A new variable was created and can be used in the system. Remember to give it a value before using it'
        ];
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

        return view('admin/settings/manage_settings', [
            'breadcrumbs' => $breadcrumbs,
            'text_parts'  => $text_parts,
            'in_sidebar'  => $sidebar_link,
            'settings'    => $settings,
            'data_types'  => array("string" => "String / Alphanumeric Values", "text" => "Text", "numeric" => "Numeric Only", "date" => "Date / DateTime Value"),
            'allowed'     => $allowed
        ]);
    }

    public function update_settings(Request $request)
    {
        $user = Auth::user();
        if ( ! $user || ! $user->is_back_user())
        {
            return [
                'success'   => false,
                'title'     => 'Login Error',
                'errors'    => 'You need to be logged in to access this function'
            ];
        }

        $vars = $request->only('contained', 'data_type', 'description', 'max_value', 'min_value', 'name', 'system_internal_name', 'id');
        $fillable = [
            'name'                  => $vars['name'],
            'system_internal_name'  => $vars['system_internal_name'],
            'description'           => $vars['description'],
            'constrained'           => $vars['contained'] == "true" ? 1 : 0,
            'data_type'             => $vars['data_type'],
            'min_value'             => $vars['min_value'] * 1 ? $vars['min_value'] : 0,
            'max_value'             => $vars['max_value'] * 1 ? $vars['max_value'] : 0
        ];

        $settingValidator = Validator::make($fillable, Settings::rules('UPDATE'), Settings::$validationMessages, Settings::$attributeNames);
        if ($settingValidator->fails())
        {
            return array(
                'success'   => false,
                'title'     => 'Error Shop Details',
                'errors'    => $settingValidator->getMessageBag()->toArray()
            );
        }
        else
        {
            $settings = Settings::where("id", $vars["id"])->get()->first();
            $settings->update($fillable);

            return [
                'success'   => true,
                'title'     => 'New Setting Variable Created',
                'message'   => 'A new variable was created and can be used in the system. Remember to give it a value before using it'
            ];
        }
    }

    public function delete_settings(Request $request)
    {
        $user = Auth::user();
        if ( ! $user || ! $user->is_back_user())
        {
            return [
                'success'   => false,
                'title'     => 'Login Error',
                'errors'    => 'You need to be logged in to access this function'
            ];
        }

        if ( ! Settings::where("id", $request->input('id'))->delete()) {
            return array(
                'success'   => false,
                'title'     => 'Error Shop Details',
                'errors'    => 'Database error'
            );
        }
        else {
            return [
                'success'   => true,
                'title'     => 'Delete settings',
                'message'   => 'Delete setting was created'
            ];
        }
    }

    public function get_items_settings(Request $request)
    {
        $user = Auth::user();
        if ( ! $user || ! $user->is_back_user())
        {
            return [
                'success'   => false,
                'title'     => 'Login Error',
                'errors'    => 'You need to be logged in to access this function'
            ];
        }

        return DB::table("allowed_setting_values")->where("setting_id", $request->input("settings_id"))->get();
    }

    public function add_items_settings(Request $request)
    {
        $user = Auth::user();
        if ( ! $user || ! $user->is_back_user())
        {
            return [
                'success'   => false,
                'title'     => 'Login Error',
                'errors'    => 'You need to be logged in to access this function'
            ];
        }

        DB::table("allowed_setting_values")->where("setting_id", $request->input("setting_id"))->delete();

        $check = TRUE;
        if ($request->input("list"))
        {
            foreach ($request->input("list") as $row)
            {
                $fillable = array(
                    "item_value" => $row["name"],
                    "caption" => $row["value"],
                    "setting_id" => $request->input("setting_id")
                );

                if ( ! DB::table("allowed_setting_values")->insert($fillable))
                {
                    $check = FALSE;
                }
            }
        }

        if ( ! $check)
        {
            return array(
                'success'   => false,
                'title'     => 'Database error',
                'errors'    => 'Database error'
            );
        }
        else
        {
            return [
                'success'   => true,
                'title'     => 'Add Items Settings',
                'message'   => 'Add Items Settings'
            ];
        }
    }

    public function get_settings(Request $request)
    {
        $user = Auth::user();
        if ( ! $user || ! $user->is_back_user())
        {
            return [
                'success'   => false,
                'title'     => 'Login Error',
                'errors'    => 'You need to be logged in to access this function'
            ];
        }
        return Settings::where("id", $request->input('settings_id'))->get()->first();
    }

    public static function get_setting_value_by_name($settingName) {
        $value = Cache::remember($settingName, 1440, function() use ($settingName) {
            $setting = Settings::with('constraint_values')->with('application_setting')->where("system_internal_name", '=', $settingName)->first();
            if ($setting){
                if ($setting->constrained==0){
                    // free value variable so we get the value
                    if ( isset($setting->application_setting->unconstrained_value)){
                        return $setting->application_setting->unconstrained_value;
                    }
                    else {
                        return false;
                    }
                }
                else{
                    // constrained value variable so we get the selected allowed value
                    foreach ($setting->constraint_values as $single){
                        if ($setting->application_setting->allowed_setting_value_id === $single->id){
                            return $single->caption;
                        }
                    }

                    return false;
                }
            }
            else {
                return false;
            }
        });
        return $value;
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

        return view('admin/settings/rankedin_integration', [
            'breadcrumbs' => $breadcrumbs,
            'text_parts'  => $text_parts,
            'in_sidebar'  => $sidebar_link,
            'application_key'   => $app_key
        ]);
    }
}
