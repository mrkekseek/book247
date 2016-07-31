<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MembershipRestriction extends Model
{
    protected $table = 'membership_restrictions';

    public static $attributeNames = array(
        'membership_id'     => 'Membership ID',
        'restriction_id'  => 'Restriction Type',
        'value'         => 'Value',
        'min_value'     => 'Minimum Value',
        'max_value'     => 'Maximum Value',
        'time_start'    => 'Time Start',
        'time_end'      => 'Time End',
    );

    public static $message = array();

    protected $fillable = [
        'membership_id',
        'restriction_id',
        'value',
        'min_value',
        'max_value',
        'time_start',
        'time_end',
    ];

    public static function rules($method, $id){
        switch($method) {
            case 'GET':
            case 'DELETE': {
                return [];
            }
            case 'POST':
            case 'PUT':
            case 'PATCH':{
                return [
                    'membership_id'     => 'required|exists:membership_plans,id',
                    'restriction_id'    => 'required|exists:membership_restriction_types,id',
                    'value'         => 'required|min:1',
                    'min_value'     => 'required|min:1',
                    'max_value'     => 'required|min:1',
                    'time_start'    => 'date_format:H:i',
                    'time_end'      => 'date_format:H:i',
                ];
            }
            default:break;
        }
    }

    public function membership_plan(){
        return $this->belongsTo('App\MembershipPlan', 'membership_id', 'id');
    }

    public function restriction_title(){
        return $this->belongsTo('App\MembershipRestrictionType', 'restriction_id', 'id');
    }
}
