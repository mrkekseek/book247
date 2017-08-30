<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Settings extends Model
{
    protected $table = 'settings';

    public static $attributeNames = array(
        'name'          => 'Setting Name',
        'system_internal_name'  => 'System Internal Reference Name',
        'description'   => 'Setting Description',
        'constrained'   => 'Setting Constrained',
        'data_type'     => 'Data Type',
        'min_value'     => 'Min Value',
        'max_value'     => 'Max Value',
        'is_protected'  => 'Is Protected',
        'setting_group' => 'Setting Group',
        'visibility'    => 'Visibility'
    );

    public static $validationMessages = array();

    protected $fillable = [
        'name',
        'system_internal_name',
        'description',
        'constrained',
        'data_type',
        'min_value',
        'max_value',
        'is_protected',
        'setting_group',
        'visibility'
    ];

    public static function rules($method, $id = 0)
    {
        switch($method){
            case 'GET':
            case 'DELETE':
            {
                return [];
            }
            case 'POST':
            {
                return [
                    'name'                  => 'required|unique:settings,name',
                    'system_internal_name'  => 'required|unique:settings,system_internal_name',
                    'description'   => 'required|min:5',
                    'constrained'   => 'required',
                    //'data_type'     => 'required',
                    'min_value'     => 'numeric',
                    'max_value'     => 'numeric',
                    'is_protected'  => 'in:0,1',
                    'setting_group' => 'exists:settings_group,id',
                    'visibility'    => 'in:all,club,federation'
                ];
            }
            case 'UPDATE':
            {
                return [
                    'name'                  => 'required|unique:settings,id,name',
                    'system_internal_name'  => 'required|unique:settings,id,system_internal_name',
                    'description'   => 'required|min:5',
                    'constrained'   => 'required',
                   // 'data_type'     => 'required',
                    'min_value'     => '',
                    'max_value'     => '',
                    'is_protected'  => 'in:0,1',
                    'setting_group' => 'exists:settings_group,id',
                    'visibility'    => 'in:all,club,federation'
                ];
            }
            case 'UPDATE':
            {
                return [
                    'name'                  => 'required|unique:settings,id,name',
                    'system_internal_name'  => 'required|unique:settings,id,system_internal_name',
                    'description'   => 'required|min:5',
                    'constrained'   => 'required',
                    'data_type'     => 'required',
                    'min_value'     => '',
                    'max_value'     => '',
                    'is_protected'  => 'in:0,1',
                    'setting_group' => 'exists:settings_group,id',
                    'visibility'    => 'in:all,club,federation'
                ];
            }
            case 'PUT':
            case 'PATCH':
            {
                return [
                    'name'          => 'required|unique:settings,name'.($id ? ",$id,id" : ''),
                    'system_internal_name'  => 'required|unique:settings,system_internal_name'.($id ? ",$id,id" : ''),
                    'description'   => 'required|min:5',
                    'constrained'   => 'required',
                    'data_type'     => 'required',
                    'min_value'     => '',
                    'max_value'     => '',
                    'is_protected'  => 'in:0,1',
                    'setting_group' => 'exists:settings_group,id',
                    'visibility'    => 'in:all,club,federation'
                ];
            }
            default:break;
        }
    }

    public function constraint_values(){
        return $this->hasMany('App\allowedSettingValue','setting_id', 'id');
    }

    public function application_setting(){
        return $this->hasOne('App\applicationSetting', 'setting_id', 'id');
    }

    public static function get_formatted(){
        $formattedSettings = [];

        $allSettings = Cache::remember('settings', 3660, function() {
            return Settings::all();
        });

        if ($allSettings){
            foreach($allSettings as $single){
                $formattedSettings[$single->system_internal_name] = $single;
            }
        }

        return $formattedSettings;
    }

    public static function getValue($settingName){
        $value = Cache::remember('setting_'.$settingName, 3660, function() use ($settingName) {
            $setting = Settings::with('applicationSetting')->where('system_internal_name','=',$settingName)->get()->first();
            if ($setting){
                if (isset($setting->applicationSetting)) {
                    // check type of setting and return correct variable value
                    if ($setting->constrained == 1){
                        // we have preselected values
                        $returnVal = allowedSettingValue::where('id','=',$setting->applicationSetting->allowed_setting_value_id)->get()->first();
                        if ($returnVal){
                            return $returnVal->item_value;
                        }
                        else{
                            return '-';
                        }
                    }
                    else{
                        return $setting->applicationSetting->unconstrained_value;
                    }
                }
                else {
                    return '-';
                }
            }
            else{
                return '-';
            }
        });

        return $value;
    }
}
