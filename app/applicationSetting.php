<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class applicationSetting extends Model
{
    protected $table = 'allowed_setting_values';

    public static $attributeNames = array(
        'setting_id'                => 'Setting ID',
        'allowed_setting_value_id'  => 'Allowed Setting Value ID',
        'unconstrained_value'       => 'Unconstrained Value',
        'updated_by_id'             => 'Updated By ID'
    );

    public static $message = array();

    protected $fillable = [
        'setting_id',
        'allowed_setting_value_id',
        'unconstrained_value'       => 'Unconstrained Value',
        'updated_by_id'             => 'Updated By ID'
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
        return $this->BelongsTo('App\Settings', 'id', 'setting_id');
    }
}
