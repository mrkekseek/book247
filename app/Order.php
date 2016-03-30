<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';

    public static $attributeNames = array(
        'employee_id'   => 'Employee',
        'buyer_id'      => 'Buyer',
        'order_number'  => 'Order Number',
        'discount_type' => 'Discount Type',
        'discount_amount' => 'Discount mount',
        'status'        => 'Order Status',
    );

    public static $message = array();

    protected $fillable = [
        'employee_id',
        'buyer_id',
        'order_number',
        'discount_type',
        'discount_amount',
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
                    'employee_id'       => 'required|exists:users,id',
                    'buyer_id'          => 'required|integer',
                    'order_number'      => 'required|unique:orders,order_number',
                    'discount_type'     => 'exists:discount_types,id',
                    'discount_amount'   => 'numeric|min:1|max:10',
                    'status'            => 'required|in:pending,ordered,processing,completed,cancelled,declined,incomplete,preordered'
                ];
            }
            case 'PUT':
            case 'PATCH':
            {
                return [
                    'employee_id'       => 'required|exists:users,id',
                    'buyer_id'          => 'required|integer',
                    'order_number'      => 'required|unique:orders,order_number,'.($id ? "$id,id" : ''),
                    'discount_type'     => 'exists:discount_types,id',
                    'discount_amount'   => 'numeric|min:1|max:10',
                    'status'            => 'required|in:pending,ordered,processing,completed,cancelled,declined,incomplete,preordered'
                ];
            }
            default:break;
        }
    }
}
