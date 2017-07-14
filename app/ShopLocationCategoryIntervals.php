<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShopLocationCategoryIntervals extends Model
{
    protected $table = 'shop_location_category_intervals';
    protected $primaryKey = 'id';

    public static $attributeNames = array(
        'location_id'   => 'Location ID',
        'category_id'   => 'Category ID',
        'time_interval' => 'Time Interval',
        'added_by'      => 'Added By Employee/Owner',
        'is_locked'     => 'Is Locked'
    );
    public static $validationMessages = array();

    protected $fillable = array(
        'location_id',
        'category_id',
        'time_interval',
        'is_locked',
        'added_by'
    );

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
                    'location_id'   => 'required|exists:shop_locations,id',
                    'category_id'   => 'required|exists:shop_resource_categories,id',
                    'time_interval' => 'required|integer|min:5|max:180',
                    'added_by'      => 'required|exists:users,id',
                    'is_locked'     => 'in:0,1'
                ];
            }
            case 'PUT':
            case 'PATCH':
            {
                return [
                    'location_id'   => 'required|exists:shop_locations,id',
                    'category_id'   => 'required|exists:shop_resource_categories,id',
                    'time_interval' => 'required|integer|min:5|max:180',
                    'added_by'      => 'exists:users,id',
                    'is_locked'     => 'in:0,1'
                ];
            }
            default:break;
        }
    }

    public function shop(){
        return $this->hasOne('App\ShopLocations','id','location_id');
    }

    public function activity(){
        return $this->hasOne('App\ShopResourceCategory','id','category_id');
    }
}
