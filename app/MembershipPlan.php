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
        'binding_period'=> 'Binding Period',
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
        'binding_period',
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
                    'price_id'              => 'required',
                    'plan_period'           => 'required|in:7,14,30,90,180,360',
                    'binding_period'        => 'required|numeric',
                    'administration_fee_name'   => 'required|min:3',
                    'administration_fee_amount' => 'required|numeric',
                    'short_description'     => 'required|min:20',
                    'description'      => '',
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
                    'plan_period'           => 'required|in:7,14,30,90,180,360',
                    'binding_period'        => 'required|numeric',
                    'administration_fee_name'   => 'required|min:3',
                    'administration_fee_amount' => 'required|numeric',
                    'short_description'     => 'required|min:20',
                    'description'      => '',
                ];
            }
            default:break;
        }
    }

    public function get_price(){
        $price = MembershipPlanPrice::select('price','discount','created_at')->where('membership_id','=',$this->id)->orderBy('created_at','desc')->get()->first();
        if ($price) {
            $unformated_date = $price->created_at;
            $price->added_on = $unformated_date->format('d-m-Y');
        }

        return $price;
    }

    public function price(){
        return $this->hasMany('App\MembershipPlanPrice', 'id', 'price_id');
    }

    public function restrictions(){
        return $this->hasMany('App\MembershipRestriction', 'membership_id', 'id');
    }

    public function add_restriction($type, $attributes){

    }

    public function get_restrictions($all_values = false){
        $restrictions = array();

        $plan_restrictions = MembershipRestriction::with('restriction_title')->where('membership_id','=',$this->id)->orderBy('restriction_id','asc')->get();
        foreach($plan_restrictions as $restriction){
            $formatted = $restriction->format_for_display_boxes();
            $to_send = [
                'id'            => $restriction->id,
                'title'         => $restriction->restriction_title->title,
                'name'          => $restriction->restriction_title->name,
                'description'   => $formatted['description'],
                'color'         => $formatted['color'],
            ];

            if ($all_values){
                $to_send['value']       = $restriction->value;
                $to_send['min_value']   = $restriction->min_value;
                $to_send['max_value']   = $restriction->max_value;
                $to_send['time_start']  = $restriction->time_start;
                $to_send['time_end']    = $restriction->time_end;
                $to_send['special_permissions'] = $restriction->special_permissions;
            }

            $restrictions[] = $to_send;
        }

        return $restrictions;
    }
}
