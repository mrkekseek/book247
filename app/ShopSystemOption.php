<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShopSystemOption extends Model
{
    protected $table = 'shop_system_options';

    public static $attributeNames = array(
        'shop_location_id'  => 'Shop Location ID',
        'var_name'  => 'Variable Name',
        'var_value' => 'Variable Value'
    );

    public static $message = array();

    protected $fillable = [
        'shop_location_id',
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
                    'shop_location_id'  => 'required|exists:shop_locations,id',
                    'var_name'  => 'required|min:3',
                    'var_value' => 'required|min:1',
                ];
            }
            case 'PUT':
            case 'PATCH':
            {
                return [
                    'shop_location_id'  => 'required|exists:shop_locations,id',
                    'var_name'  => 'required|min:3',
                    'var_value' => 'required|min:1',
                ];
            }
            default:break;
        }
    }

    public function shopLocation(){
        return $this->belongsTo('App\ShopLocations','id','shop_location_id');
    }
}
