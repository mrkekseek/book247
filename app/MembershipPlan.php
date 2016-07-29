<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MembershipPlan extends Model
{
    protected $table = 'membership_plans';

    public static $attributeNames = array(
        'name'      => 'Membership Name',
        'plan_calendar_color' => 'Membership Plan Color',
        'status'    => 'Status',
        'price_id'  => 'Price',
        'plan_period'   => 'Plan Period of Validity',
        'administration_fee_name' => 'Administration Fee Amount',
        'administration_fee_amount' => 'Short Information',
        'short_description' => 'Administration Fee Name',
        'long_description'  => 'Description'
    );

    public static $message = array();

    protected $fillable = [
        'name',
        'plan_calendar_color',
        'status',
        'price_id',
        'plan_period',
        'administration_fee_name',
        'administration_fee_amount',
        'short_description',
        'long_description'
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
                    'name'                  => 'required|min:3|max:75|unique:membership_plans,name',
                    'plan_calendar_color'   => 'required|min:7|max:7',
                    'status'                => 'required|in:active,pending,suspended,deleted',
                    'price_id'              => 'required|exists:membership_plan_prices,id',
                    'plan_period'           => 'required|in:7d,14d,1m,3m.6m,12m',
                    'administration_fee_name'   => 'required|min:3',
                    'administration_fee_amount' => 'required|numeric|min:1',
                    'short_description'     => 'required|min:50',
                    'long_description'      => '',
                ];
            }
            case 'PUT':
            case 'PATCH':
            {
                return [
                    'name'                  => 'required|min:3|max:75|unique:membership_plans,name'.($id ? ",$id,id" : ''),
                    'plan_calendar_color'   => 'required|min:7|max:7',
                    'status'                => 'required|in:active,pending,suspended,deleted',
                    'price_id'              => 'required|exists:membership_plan_prices,id',
                    'plan_period'           => 'required|in:7d,14d,1m,3m.6m,12m',
                    'administration_fee_name'   => 'required|min:3',
                    'administration_fee_amount' => 'required|numeric|min:1',
                    'short_description'     => 'required|min:50',
                    'long_description'      => '',
                ];
            }
            default:break;
        }
    }

    public function get_price(){
        $price = MembershipPlanPrice::select('entry_price','created_at')->where('membership_id','=',$this->id)->orderBy('created_at','desc')->get()->first();
        if ($price) {
            $unformated_date = $price->created_at;
            $price->added_on = $unformated_date->format('d-m-Y');
        }

        return $price;
    }

    public function price(){
        return $this->hasMany('App\MembershipPlanPrice', 'price_id', 'id');
    }

    public function membership_restrictions(){
        return $this->hasMany('App\MembershipRestrictions');
    }
}
