<?php

namespace App\Http\Controllers;

use App\Settings;
use Illuminate\Http\Request;

use DB;
use App\Http\Requests;
use Auth;
use Illuminate\Support\Facades\Validator;

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

        $fillable = array(
                "setting_id" => $request->input("setting_id"),
                "unconstrained_value" => $request->input("value"),
                "updated_by_id" => Auth::user()->id
            );

        $setting = DB::table("application_settings")->where("setting_id", $request->input("setting_id"))->where("updated_by_id", Auth::user()->id);

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

        $setting = DB::table("application_settings")->where("setting_id", $request->input("setting_id"))->where("updated_by_id", Auth::user()->id);

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

        $allowed = array();
        foreach (DB::table("allowed_setting_values")->get() as $row)
        {
            $allowed[$row->setting_id][] = $row;
        }

        $app_settings = array();
        foreach (DB::table("application_settings")->get() as $row)
        {
            $app_settings[$row->updated_by_id][$row->setting_id] = $row->unconstrained_value != '' ? $row->unconstrained_value : $row->allowed_setting_value_id;
        }

        $settings = array();
        foreach (Settings::all() as $row) 
        {
            $row['value'] = isset($app_settings[Auth::user()->id][$row->id]) ? $app_settings[Auth::user()->id][$row->id] : "";
            $row['allowed'] = isset($allowed[$row->id]) ? $allowed[$row->id] : FALSE;
            $settings[] = $row;
        }

        //$a = $this->get_setting_value_by_name('globalWebsite_auto_show_status_change');
        //xdebug_var_dump($a); exit;

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
            'max_value'             => $vars['min_value'] * 1 ? $vars['min_value'] : 0
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

        if ( ! Settings::where("id", $request->input('id'))->delete())
        {
            return array(
                'success'   => false,
                'title'     => 'Error Shop Details',
                'errors'    => 'Database error'
            );
        }
        else
        {
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

        $setting = Settings::with('constraint')->with('application_setting')->where("system_internal_name", '=', $settingName)->first();

        //echo $setting->id;
        //xdebug_var_dump($setting->application_setting);
        //xdebug_var_dump($setting->constraint);
        if ($setting->constraint){
            if ($setting->constrained===0){
                // free value variable
                if ($setting->application_setting->unconstrained_value){
                    return $setting->application_setting->unconstrained_value;
                }
                else {
                    return false;
                }
            }
            else{
                // constrained value variable
                return $setting->application_setting->caption;
            }
        }
        else {
            return false;
        }
    }
}
