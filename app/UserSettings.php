<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserSettings extends Model
{
    protected $table = 'user_settings';

    public static $attributeNames = array(
        'user_id'   => 'User ID',
        'var_name'  => 'Variable Name',
        'var_value' => 'Variable Value'
    );

    public static $message = array();

    protected $fillable = [
        'user_id',
        'var_name',
        'var_value'
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
                    'user_id'   => 'required|exists:users,id',
                    'var_name'  => 'required|min:3',
                    'var_value' => 'required|min:1',
                ];
            }
            case 'PUT':
            case 'PATCH':
            {
                return [
                    'user_id'   => 'required|exists:users,id',
                    'var_name'  => 'required|min:3',
                    'var_value' => 'required|min:1',
                ];
            }
            default:break;
        }
    }

    public static function get_general_settings($user_id, $var_name){
        if (is_array($var_name)){
            $generalSettings = UserSettings::where('user_id','=',$user_id)->whereIn('var_name',$var_name)->get();
        }
        else{
            $generalSettings = UserSettings::where('user_id','=',$user_id)->where('var_name','=',$var_name)->get();
        }

        $settings = [];
        if($generalSettings){
            foreach($generalSettings as $val){
                $settings[$val->var_name] = $val->var_value;
            }
        }

        return $settings;
    }
}
