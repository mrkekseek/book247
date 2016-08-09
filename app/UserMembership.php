<?php

namespace App;

use Carbon\Carbon;
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
        'binding_period'    => 'Binding Period',
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
        'binding_period',
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
                    'binding_period'    => 'required|numeric',
                    'price'     => 'required|numeric',
                    'discount'  => 'numeric',
                    'membership_restrictions'   => 'required|min:3',
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
                    'binding_period'    => 'required|numeric',
                    'price'     => 'required|numeric',
                    'discount'  => 'numeric',
                    'membership_restrictions'   => 'required|min:3',
                    'signed_by' => 'required|exists:users,id',
                    'status'    => 'required|in:active,suspended,canceled,expired'
                ];
            }
            default:break;
        }
    }

    public function get_plan_restrictions(){
        $my_restrictions = json_decode($this->membership_restrictions);
        foreach($my_restrictions as $rest){
            $restrictions[] = [
                'id'            => $rest->id,
                'title'         => $rest->title,
                'name'          => $rest->name,
                'description'   => $rest->description,
                'color'         => $rest->color,
                'value'         => $rest->value,
                'min_value'     => $rest->min_value,
                'max_value'     => $rest->max_value,
                'time_start'    => $rest->time_start,
                'time_end'      => $rest->time_end,
            ];
        }

        return $restrictions;
    }

    public function get_plan_details(){
        $invoice_period = [
            '7'   => 'once every 7 days',
            '14'  => 'once every 14 days',
            '30'  => 'one per month',
            '90'  => 'once every three months',
            '180' => 'once every six months',
            '360' => 'once per year'
        ];

        if ($this->user_id==$this->signed_by){
            $signed_by_link = '';
            $signed_by_name = 'Self';
        }
        else{
            $user = User::where('id','=',$this->signed_by)->get()->first();
            if ($user){
                $signed_by_name = $user->first_name.' '.$user->middle_name.' '.$user->last_name;
                if ($user->hasRole(['front_member','front_user'])){
                    // a member
                    $signed_by_link = route('admin/front_users/view_user/',$user->id);
                }
                else{
                    // a user
                    $signed_by_link = route('admin/back_users/view_user/',$user->id);
                }
            }
            else{
                $signed_by_link = '';
                $signed_by_name = '??ERROR??';
            }
        }

        $plan_details = [
            'price'         => $this->price,
            'day_start'     => Carbon::createFromFormat('Y-m-d',$this->day_start)->format('j F, Y'),
            'day_stop'      => Carbon::createFromFormat('Y-m-d',$this->day_stop)->format('j F, Y'),
            'membership_name'   => $this->membership_name,
            'invoice_period'    => $invoice_period[$this->invoice_period],
            'binding_period'    => $this->binding_period,
            'discount'      => $this->discount,
            'signed_by_name'    => $signed_by_name,
            'signed_by_link'    => $signed_by_link
        ];

        return $plan_details;
    }
}
