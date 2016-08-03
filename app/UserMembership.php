<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserMembership extends Model
{
    protected $table = 'user_memberships';

    public static $attributeNames = array(
        'user_id'       => 'User ID',
        'membership_id' => 'Membership ID',
        'day_start'     => 'Start Day',
        'day_stop'      => 'End Day',
        'membership_name'   => 'Membership Name',
        'invoice_period'    => 'Invoice Period',
        'price'         => 'Price',
        'discount'      => 'Discount',
        'membership_restrictions' => 'Membership Restrictions',
        'signed_by'     => 'Signed By',
    );

    public static $message = array();

    protected $fillable = [
        'user_id',
        'membership_id',
        'day_start',
        'day_stop',
        'membership_name',
        'invoice_period',
        'price',
        'discount',
        'membership_restrictions',
        'signed_by',
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
                    'membership_id' => 'required|exists:membership_plans,id',
                    'day_start' => 'required|date',
                    'day_stop'  => 'required|date',
                    'membership_name'   => 'required|min:3',
                    'invoice_period'    => 'required|numeric',
                    'price'     => 'required|float',
                    'discount'  => 'float',
                    'membership_restrictions'   => 'required|min3',
                    'signed_by' => 'required|exists:users,id',
                    'status'    => 'required|in:active,suspended,canceled,expired'
                ];
            }
            case 'PUT':
            case 'PATCH':
            {
                return [
                    'user_id'   => 'required|exists:users,id',
                    'membership_id' => 'required|exists:membership_plans,id',
                    'day_start' => 'required|date',
                    'day_stop'  => 'required|date',
                    'membership_name'   => 'required|min:3',
                    'invoice_period'    => 'required|numeric',
                    'price'     => 'required|float',
                    'discount'  => 'float',
                    'membership_restrictions'   => 'required|min3',
                    'signed_by' => 'required|exists:users,id',
                    'status'    => 'required|in:active,suspended,canceled,expired'
                ];
            }
            default:break;
        }
    }


}
