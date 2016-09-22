<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShopOpeningHour extends Model
{
    protected $table = 'shop_opening_hours';
    protected $primaryKey = 'id';

    public static $attributeNames = array(
        'shop_location_id'  => 'Shop Name',
        'days'              => 'Day of Week',
        'time_start'        => 'Time start',
        'time_stop'         => 'Time stop',
        'date_start'        => 'Date start',
        'date_stop'         => 'Date stop',
        'type'              => 'Type'
    );
    public static $validationMessages = array(

    );

    protected $fillable = array(
        'shop_location_id',
        'days',
        'time_start',
        'time_stop',
        'date_start',
        'date_stop',
        'type'
    );

    public function shop_location(){
        return $this->hasOne('App\ShopLocation', 'id', 'shop_location_id');
    }

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
                    'days'          => 'required|min:1|max:50',
                    'time_start'    => 'required|date_format:"H:i:s"',
                    'time_stop'     => 'required|date_format:"H:i:s"',
                    'date_start'    => 'date',
                    'date_stop'     => 'date',
                    'type'          => 'required|in:open_hours,break_hours,close_hours'
                ];
            }
            case 'PUT':
            case 'PATCH':
            {
                return [
                    'shop_location_id'  => 'required|exists:shop_locations,id',
                    'days'          => 'required|min:1|max:50',
                    'time_start'    => 'required|date_format:"H:i:s"',
                    'time_stop'     => 'required|date_format:"H:i:s"',
                    'date_start'    => 'date',
                    'date_stop'     => 'date',
                    'type'          => 'required|in:open_hours,break_hours,close_hours'
                ];
            }
            default:break;
        }
    }
}
