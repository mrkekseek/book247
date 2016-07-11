<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BookingInvoiceItem extends Model
{
    protected $table = 'booking_invoice_items';

    public static $attributeNames = array(
        'booking_invoice_id'=> 'Invoice ID',
        'booking_id'        => 'Invoice Booking ID',
        'location_name' => 'Location Name',
        'resource_name' => 'Resource Name',
        'quantity'      => 'Quantity',
        'booking_date'  => 'Booking Date',
        'booking_time_interval' => 'Booking Time Interval',
        'price'         => 'Price',
        'vat'           => 'VAT',
        'discount'      => 'Discount',
        'total_price'   => 'Total Price'
    );

    public static $message = array();

    protected $fillable = [
        'booking_invoice_id',
        'booking_id',
        'location_name',
        'resource_name',
        'quantity',
        'booking_date',
        'booking_time_interval',
        'price',
        'vat',
        'discount',
        'total_price'
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
                    'location_name'         => 'required',
                    'resource_name'         => 'required',
                    'quantity'              => 'required|integer',
                    'booking_date'          => 'required|date',
                    'booking_time_interval' => 'required',
                    'price'                 => 'required|numeric',
                    'vat'                   => 'required|numeric',
                    'discount'              => 'required|numeric',
                    'total_price'           => 'required|numeric'
                ];
            }
            case 'PUT':
            case 'PATCH':
            {
                return [
                    'booking_invoice_id'    => 'required|exists:booking_invoices,id',
                    'booking_id'            => 'required|exists:bookings,id',
                    'location_name'         => 'required',
                    'resource_name'         => 'required',
                    'quantity'              => 'required|integer',
                    'booking_date'          => 'required|date',
                    'booking_time_interval' => 'required',
                    'price'                 => 'required|numeric',
                    'vat'                   => 'required|numeric',
                    'discount'              => 'required|numeric',
                    'total_price'           => 'required|numeric'
                ];
            }
            default:break;
        }
    }
}
