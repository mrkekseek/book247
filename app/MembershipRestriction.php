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
        'special_permissions'   => 'Special Permissions'
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
        'special_permissions'
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

    public function format_for_display_boxes(){
        switch($this->restriction_title->name){
            case 'time_of_day' :
                $days_in = [];
                $days = json_decode($this->value);
                foreach ($days as $day){
                    $days_in[] = jddayofweek($day-1, 1);
                }

                $description = 'Included days : <b>'.implode(', ',$days_in).'</b> between <b>'.$this->time_start.' - '.$this->time_end.'</b>';
                $specials = json_decode($this->special_permissions);
                if (sizeof($specials)>0){
                    $description .= '<br />Special Restrictions : ';
                    $description_sp = [];
                    foreach($specials as $key=>$special){
                        switch($key){
                            case 'special_current_day' :
                                if ($special==1) {
                                    $description_sp[] = 'booking today';
                                }
                                elseif($special==0){
                                    $description_sp[] = 'no booking today';
                                }
                                break;
                            case 'special_days_ahead' :
                                if ($special>0) {
                                    $description_sp[] = 'book for ' . $special . ' days from midnight';
                                }
                                break;
                        }
                    }
                    $description.=implode(', ',$description_sp);
                }

                $color = 'note-info';
                break;
            case 'open_bookings' :
                $description = 'Number of active open bookings included in membership plan : <b>'.$this->value.'</b>';
                $color = 'note-success';
                break;
            case 'cancellation' :
                $description = 'Number of hours before booking start until cancellation is possible : <b>'.$this->value.' hours</b>';
                $color = 'note-warning';
                break;
            case 'price' :
                $description = '';
                $color = 'note-success';
                break;
            case 'included_activity' :
                $in_activities = ShopResourceCategory::whereIn('id', json_decode($this->value))->get();
                $available = array();
                foreach($in_activities as $activity){
                    $available[] = $activity->name;
                }

                $description = 'Following activities are included : <b>'.implode(', ', $available).'</b>';
                $color = 'note-success';
                break;
            case 'booking_time_interval' :
                $hour_day = [
                    "1"   => '1 hour',
                    "2"   => '2 hours',
                    "3"   => '3 hours',
                    "4"   => '4 hours',
                    "5"   => '5 hours',
                    "6"   => '6 hours',
                    "9"   => '9 hours',
                    "12"  => '12 hours',
                    "24"  => '1 day',
                    "48"  => '2 days',
                    "72"  => '3 days',
                    "96"  => '4 days',
                    "120" => '5 days',
                    "144" => '6 days',
                    "168" => '7 days',
                    "336" => '14 days'
                ];
                $description = 'Booking can be made <b> '.$hour_day[$this->max_value].' </b> up front.';
                $color = 'note-info';
                break;
            default :
                $description = '';
                $color = '';
            break;
        }

        return [
            'description'   => $description,
            'color'         => $color,
            'value'         => $this->value,
            'min_value'     => $this->min_value,
            'max_value'     => $this->max_value,
            'time_start'    => $this->time_start,
            'time_end'      => $this->time_end,
            'special_permissions'   => $this->special_permissions
        ];
    }
}
