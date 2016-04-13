<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShopOpeningHour extends Model
{
    protected $table = 'shop_opening_hours';
    protected $primaryKey = 'id';

    public static $attributeNames = array(
        'location_id'   => 'Shop Name',
        'day_of_week'   => 'Day of Week',
        'specific_day'  => 'Specific Day',
        'entry_type'    => 'Entry Type',
        'open_at'   => 'Open At',
        'close_at'  => 'Close At',
        'break_from'    => 'Break From',
        'break_to'  => 'Break To',
    );
    public static $validationMessages = array(

    );

    protected $fillable = array(
        'location_id',
        'day_of_week',
        'specific_day',
        'entry_type',
        'open_at',
        'close_at',
        'break_from',
        'break_to',
    );

    public function shop_location(){
        return $this->hasOne('App\ShopLocation', 'id', 'location_id');
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
                    'location_id'   => 'exists|shop_locations:id',
                    'day_of_week'   => 'required|min:1|max:7',
                    'specific_day'  => 'required|date',
                    'entry_type'    => 'required|in_array:day,specific',
                    'open_at'   => 'required',
                    'close_at'  => 'required',
                    'break_from'    => 'required',
                    'break_to'  => 'required',
                ];
            }
            case 'PUT':
            case 'PATCH':
            {
                return [
                    'location_id'   => 'exists|shop_locations:id',
                    'day_of_week'   => 'required|min:1|max:7',
                    'specific_day'  => 'required|date',
                    'entry_type'    => 'required|in_array:day,specific',
                    'open_at'   => 'required',
                    'close_at'  => 'required',
                    'break_from'    => 'required',
                    'break_to'  => 'required',
                ];
            }
            default:break;
        }
    }
}
