<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MembershipPlanPrice extends Model
{
    protected $table = 'membership_plan_prices';

    public static $attributeNames = array(
        'membership_id' => 'Membership ID',
        'price'         => 'Status',
        'discount'      => 'Price',
    );

    public static $message = array();

    protected $fillable = [
        'membership_id',
        'price',
        'discount'
    ];

    public static function rules($method, $id=0){
        switch($method){
            case 'GET':
            case 'DELETE':
            {
                return [];
            }
            case 'POST':
            case 'PUT':
            case 'PATCH':
            {
                return [
                    'membership_id' => 'required|exists:membership_plans,id',
                    'price'         => 'required|numeric|min:0',
                    'discount'      => 'required|numeric|min:0|max:100'
                ];
            }
            default:break;
        }
    }

    public function membership_plan(){
        return $this->belongsTo('App\MembershipPlan', 'id', 'membership_id');
    }
}
