<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BookingInvoiceItem extends Model
{
    protected $table = 'order_invoice_items';

    public static $attributeNames = array(
        'booking_invoice_id'=> 'Invoice ID',
        'booking_id'        => 'Order Item ID',
    );

    public static $message = array();

    protected $fillable = [
        'booking_invoice_id',
        'booking_id',
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
                    'booking_invoice_id'    => 'required|exists:booking_invoices,id',
                    'booking_id'            => 'required|exists:bookings,id',
                ];
            }
            case 'PUT':
            case 'PATCH':
            {
                return [
                    'booking_invoice_id'    => 'required|exists:booking_invoices,id',
                    'booking_id'            => 'required|exists:bookings,id',
                ];
            }
            default:break;
        }
    }
}
