<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $table = 'bookings';

    public static $attributeNames = array(
        'by_user_id'    => 'By Member',
        'for_user_id'   => 'For Member',
        'location_id'   => 'Location ID',
        'resource_id'   => 'Resource ID',
        'status'        => 'Booking Status',
        'date_of_booking'   => 'Date of Booking',
        'booking_time_start'=> 'Booking Start',
        'booking_time_stop' => 'Booking End',
        'payment_type'  => 'Payment Type',
        'membership_id' => 'Membership ID',
        'invoice_id'    => 'Invoice ID',
    );

    public static $message = array();

    protected $fillable = [
        'by_user_id',
        'for_user_id',
        'location_id',
        'resource_id',
        'status',
        'date_of_booking',
        'booking_time_start',
        'booking_time_stop',
        'payment_type',
        'membership_id',
        'invoice_id'
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
                    'by_user_id'        => 'required|exists:users,id',
                    'for_user_id'       => 'required|exists:users,id',
                    'location_id'       => 'required|exists:shop_locations,id',
                    'resource_id'       => 'required|exists:shop_resources,id',
                    'status'            => 'required|in:pending,active,paid,canceled',
                    'date_of_booking'   => 'required|date',
                    'booking_time_start'=> 'required|date_format:"H:i"',
                    'booking_time_stop' => 'required|date_format:"H:i',
                    'payment_type'      => 'required|in:cash,membership',
                    'membership_id'     => '',
                    'invoice_id'        => '',
                ];
            }
            case 'PUT':
            case 'PATCH':
            {
                return [
                    'by_user_id'        => 'required|exists:users,id',
                    'for_user_id'       => 'required|exists:users,id',
                    'location_id'       => 'required|exists:shop_locations,id',
                    'resource_id'       => 'required|exists:shop_resources,id',
                    'status'            => 'required|in:pending,active,paid,canceled',
                    'date_of_booking'   => 'required|date',
                    'booking_time_start'=> 'required|time',
                    'booking_time_stop' => 'required|time',
                    'payment_type'      => 'required|in:cash,membership',
                    'membership_id'     => '',
                    'invoice_id'        => '',
                ];
            }
            default:break;
        }
    }
}
