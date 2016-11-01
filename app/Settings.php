<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
        'max_value'     => 'Max Value'
    );

    public static $validationMessages = array();

    protected $fillable = [
        'name',
        'system_internal_name',
        'description',
        'constrained',
        'data_type',
        'min_value',
        'max_value'
    ];

    public static function rules($method, $id=0){
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
                    'data_type'     => 'required',
                    'min_value'     => '',
                    'max_value'     => '',
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
                ];
            }
            default:break;
        }
    }

    public function constraint(){
        return $this->hasMany('App\allowedSettingValue', 'setting_id', 'id');
    }

    public function applicationSetting(){
        return $this->hasOne('App\applicationSetting', 'setting_id', 'id');
    }

    public static function get_formatted(){
        $formattedSettings = [];

        $allSettings = Settings::all();
        if ($allSettings){
            foreach($allSettings as $single){
                $formattedSettings[$single->system_internal_name] = $single;
            }
        }

        return $formattedSettings;
    }
}
