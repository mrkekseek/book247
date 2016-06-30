<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Zizaco\Entrust\Traits\EntrustUserTrait;

class User extends Authenticatable
{
    use EntrustUserTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'middle_name', 'last_name', 'username', 'email', 'password',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function canDo($permission){

    }

    public function ability($roles, $permissions, $options){

    }

    public function roles()
    {
        return $this->belongsToMany('App\Role');
    }

    public function Addresses(){
        return $this->hasMany('App\Address');
    }

    public function ProfessionalDetail(){
        return $this->hasOne('App\ProfessionalDetail');
    }

    public function PersonalDetail(){
        return $this->hasOne('App\PersonalDetail');
    }

    public function avatar(){
        return $this->hasOne('App\UserAvatars');
    }

    public function documents(){
        return $this->hasMany('App\UserDocuments');
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
                    'first_name' => 'required|min:4|max:150',
                    'last_name' => 'required|min:4|max:150',
                    'username' => 'required|min:6|max:30|unique:users,username',
                    'password' => 'required|min:8',
                    'email' => 'required|email|email|unique:users',
                    'user_type' => 'required|exists:roles,id',
                ];
            }
            case 'PUT':
            case 'PATCH':
            {
                return [
                    'first_name' => 'required|min:4|max:150',
                    'last_name' => 'required|min:4|max:150',
                    'username' => 'required|min:6|max:30|unique:users,username',
                    'password' => 'required|min:8',
                    'email' => 'required|email|email|unique:users',
                    'user_type' => 'required|exists:roles,id',

                    'by_user_id'        => 'required|exists:users,id',
                    'for_user_id'       => 'required|exists:users,id',
                    'location_id'       => 'required|exists:shop_locations,id',
                    'resource_id'       => 'required|exists:shop_resources,id',
                    'status'            => 'required|in:pending,active,paid,canceled',
                    'date_of_booking'   => 'required|date',
                    'booking_time_start'=> 'required|time',
                    'booking_time_stop' => 'required|time',
                    'payment_type'      => 'required|in:cash,membership,recurring',
                    'membership_id'     => '',
                    'invoice_id'        => '',
                    'search_key'        => 'required|unique:bookings,search_key'.($id ? ",$id,id" : ''),
                    'payment_amount'    => 'numeric',
                ];
            }
            default:break;
        }
    }
}
