<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $table = 'order_items';

    public static $attributeNames = array(
        'order_id'      => 'Order ID',
        'product_id'    => 'Product ID',
        'quantity'      => 'Quantity',
        'status'        => 'Item Status',
        'other_details' => 'Other Details',
    );

    public static $message = array();

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'status',
        'other_details',
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
                    'order_id'      => 'required|exists:orders,id',
                    'product_id'    => 'required|exists:products,id',
                    'quantity'      => 'required|integer',
                    'status'        => 'required|in:ordered,cancelled'
                ];
            }
            case 'PUT':
            case 'PATCH':
            {
                return [
                    'order_id'      => 'required|exists:orders,id',
                    'product_id'    => 'required|exists:products,id',
                    'quantity'      => 'required|integer',
                    'status'        => 'required|in:ordered,cancelled'
                ];
            }
            default:break;
        }
    }
}
