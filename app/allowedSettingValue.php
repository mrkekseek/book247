<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class allowedSettingValue extends Model
{
    protected $table = 'allowed_setting_values';

    public static $attributeNames = array(
        'setting_id'    => 'Setting ID',
        'item_value'    => 'Item Value',
        'caption'       => 'Item Caption',
    );

    public static $message = array();

    protected $fillable = [
        'setting_id',
        'item_value',
        'caption',
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
                    'setting_id'    => 'required|exists:settings,id',
                    'item_value'    => 'required|min:1',
                    'caption'       => 'required|min:1',
                ];
            }
            case 'PUT':
            case 'PATCH':
            {
                return [
                    'setting_id'    => 'required|exists:settings,id',
                    'item_value'    => 'required|min:1',
                    'caption'       => 'required|min:1',
                ];
            }
            default:break;
        }
    }

    public function setting(){
        return $this->belongsTo('App\Settings', 'setting_id', 'id');
    }
}
