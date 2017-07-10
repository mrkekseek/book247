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
    );
    public static $validationMessages = array();

    protected $fillable = array(
        'location_id',
        'category_id',
        'time_interval',
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
                    'time_interval' => 'required|min:5|max:120',
                    'added_by'      => 'required|exists:users,id',
                ];
            }
            case 'PUT':
            case 'PATCH':
            {
                return [
                    'location_id'   => 'required|exists:shop_locations,id',
                    'category_id'   => 'required|exists:shop_resource_categories,id',
                    'time_interval' => 'required|min:5|max:120',
                    'added_by'      => 'required|exists:users,id',
                ];
            }
            default:break;
        }
    }

    public function shop(){
        return $this->hasOne('App\ShopLocations','id','location_id');
    }

    public function activity(){
        return $this->hasOne('App\ShopResourceCategiry','id','category_id');
    }
}
