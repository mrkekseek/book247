<?php

namespace App\Http\Controllers;

use App\Settings;
use Illuminate\Http\Request;

use App\Http\Requests;
use Auth;
use Illuminate\Support\Facades\Validator;

class AppSettings extends Controller
{
    public function __construct()
    {
        //$this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();
        if (!$user || !$user->is_back_user()) {
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
        ]);
    }

    public function register_new_setting(Request $request){
        $user = Auth::user();
        if (!$user || !$user->is_back_user()) {
            return [
                'success'   => false,
                'title'     => 'Login Error',
                'errors'    => 'You need to be logged in to access this function'
            ];
        }

        $vars = $request->only('contained', 'data_type', 'description', 'max_value', 'min_value', 'name', 'system_internal_name');
        $fillable = [
            'name'          => $vars['name'],
            'system_internal_name'  => $vars['system_internal_name'],
            'description'   => $vars['description'],
            'constrained'   => $vars['contained'],
            'data_type'     => $vars['data_type'],
            'min_value'     => $vars['min_value'],
            'max_value'     => $vars['max_value']
        ];

        $settingValidator = Validator::make($fillable, Settings::rules('POST'), Settings::$validationMessages, Settings::$attributeNames);
        if ($settingValidator->fails()){
            //return $validator->errors()->all();
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
}
