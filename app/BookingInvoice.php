<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BookingInvoice extends Model
{
    protected $table = 'booking_invoices';

    public static $attributeNames = array(
        'booking_id'    => 'Booking ID',
        'invoice_number'=> 'Invoice Number',
        'other_details' => 'Invoice Details',
        'status'        => 'Invoice Status',
    );

    public static $message = array();

    protected $fillable = [
        'booking_id',
        'invoice_number',
        'other_details',
        'status',
    ];

    public static function next_invoice_number(){
        $booking = BookingInvoice::select('invoice_number')->orderBy('invoice_number', 'desc')->get()->first();
        if ($booking){
            return ($booking->invoice_number+1);
        }
        else{
            return 1100;
        }
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
                    'booking_id'        => 'required|exists:bookings,id',
                    'invoice_number'    => 'required|unique:booking_invoices,invoice_number',
                    'status'            => 'required|in:pending,ordered,processing,completed,cancelled,declined,incomplete,preordered',
                ];
            }
            case 'PUT':
            case 'PATCH':
            {
                return [
                    'booking_id'        => 'required|exists:bookings,id',
                    'invoice_number'    => 'required|unique:booking_invoices,invoice_number'.($id ? ",$id,id" : ''),
                    'status'            => 'required|in:pending,ordered,processing,completed,cancelled,declined,incomplete,preordered'
                ];
            }
            default:break;
        }
    }
}
