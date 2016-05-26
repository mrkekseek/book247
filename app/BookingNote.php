<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BookingNote extends Model
{
    protected $table = 'booking_notes';

    public static $attributeNames = [
        'booking_id'    => 'Booking ID',
        'by_user_id'    => 'User ID',
        'note_title'    => 'Note Title',
        'note_body'     => 'Note Message',
        'note_type'     => 'Note Type',
        'privacy'       => 'Privacy Settings',
        'status'        => 'Status',
    ];

    public static $message = array();

    protected $fillable = [
        'booking_id',
        'by_user_id',
        'note_title',
        'note_body',
        'note_type',
        'privacy',
        'status',
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
                    'booking_id'    => 'required|exists:bookings,id',
                    'by_user_id'    => 'required|exists:users,id',
                    'note_title'    => 'required|min:10|max:150',
                    'note_body'     => 'required|min:10',
                    'note_type'     => 'required|min:5',
                    'privacy'       => 'required|in:admin,employees,everyone',
                    'status'        => 'required|in:unread,read,deleted',
                ];
            }
            case 'PUT':
            case 'PATCH':
            {
                return [
                    'booking_id'    => 'required|exists:bookings,id',
                    'by_user_id'    => 'required|exists:users,id',
                    'note_title'    => 'required|min:10|max:150',
                    'note_body'     => 'required|min:10',
                    'note_type'     => 'required|min:5',
                    'privacy'       => 'required|in:admin,employees,everyone',
                    'status'        => 'required|in:unread,read,deleted',
                ];
            }
            default:break;
        }
    }
}
