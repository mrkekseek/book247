<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderInvoice extends Model
{
    protected $table = 'order_invoices';

    public static $attributeNames = array(
        'order_id'      => 'Order ID',
        'invoice_number'=> 'Invoice Number',
        'other_details' => 'Invoice Details',
        'status'        => 'Invoice Status',
    );

    public static $message = array();

    protected $fillable = [
        'order_id',
        'invoice_number',
        'other_details',
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
                    'order_id'          => 'required|exists:orders,id',
                    'invoice_number'    => 'required|unique:order_invoices,invoice_number',
                    'status'            => 'required|in:pending,ordered,processing,completed,cancelled,declined,incomplete,preordered',
                ];
            }
            case 'PUT':
            case 'PATCH':
            {
                return [
                    'order_id'          => 'required|exists:orders,id',
                    'invoice_number'    => 'required|unique:order_invoices,invoice_number'.($id ? ",$id,id" : ''),
                    'status'            => 'required|in:pending,ordered,processing,completed,cancelled,declined,incomplete,preordered'
                ];
            }
            default:break;
        }
    }
}
