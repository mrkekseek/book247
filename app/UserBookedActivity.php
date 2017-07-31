<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserBookedActivity extends Model
{
    protected $table = 'users_booked_activities';

    public static $attributeNames = array(
        'user_id'       => 'Player ID',
        'activity_id'   => 'Sport/Activity ID',
    );

    public static $message = array();

    protected $fillable = [
        'user_id',
        'activity_id',
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
                    'user_id'       => 'required|exists:users,id',
                    'activity_id'   => 'required|exists:shop_resource_categories,id',
                ];
            }
            case 'PUT':
            case 'PATCH':
            {
                return [
                    'user_id'       => 'required|exists:users,id',
                    'activity_id'   => 'required|exists:shop_resource_categories,id',
                ];
            }
            default:break;
        }
    }

    public function activities(){
        return $this->hasOne('App\ShopResourceCategory','id','activity_id');
    }
    
    public function users(){
        return $this->hasOne('App\User','id','user_id');
    }
}
