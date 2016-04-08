<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderInvoiceItem extends Model
{
    protected $table = 'order_invoice_items';

    public static $attributeNames = array(
        'invoice_id'    => 'Invoice ID',
        'order_item_id' => 'Order Item ID',
        'product_id'    => 'Product ID',
        'product_name'  => 'Product Name',
        'product_quantity'  => 'Product Quantity',
        'product_cost'      => 'Product Cost',
        'product_price'     => 'Product Price',
        'product_manual_price'  => 'Product Manual Price',
        'product_discount'  => 'Product Discount',
        'product_vat'       => 'Product VAT',
        'product_total_cost'=> 'Product Total Cost',
    );

    public static $message = array();

    protected $fillable = [
        'invoice_id',
        'order_item_id',
        'product_id',
        'product_name',
        'product_quantity',
        'product_cost',
        'product_price',
        'product_manual_price',
        'product_discount',
        'product_vat',
        'product_total_cost',
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
                    'invoice_id'        => 'required|exists:order_invoices,id',
                    'order_item_id'     => 'required|exists:order_items,id',
                    'product_id'        => 'required|exists:products,id',
                    'product_name'      => 'required|string|between:2,150',
                    'product_quantity'  => 'required|integer|min:0',
                    'product_cost'      => 'required|numeric|min:0',
                    'product_price'     => 'required|numeric|min:0',
                    'product_manual_price'  => 'required|numeric|min:0',
                    'product_discount'  => 'required|numeric|min:0|max:100',
                    'product_vat'       => 'required|numeric|min:0|max:100',
                    'product_total_cost'    => 'required|numeric|min:0',
                ];
            }
            case 'PUT':
            case 'PATCH':
            {
                return [
                    'invoice_id'        => 'required|exists:order_invoices,id',
                    'order_item_id'     => 'required|exists:order_items,id',
                    'product_id'        => 'required|exists:products,id',
                    'product_name'      => 'required|string|between:2,150',
                    'product_quantity'  => 'required|integer|min:0',
                    'product_cost'      => 'required|numeric|min:0',
                    'product_price'     => 'required|numeric|min:0',
                    'product_manual_price'  => 'required|numeric|min:0',
                    'product_discount'  => 'required|numeric|min:0|max:100',
                    'product_vat'       => 'required|numeric|min:0|max:100',
                    'product_total_cost'    => 'required|numeric|min:0',
                ];
            }
            default:break;
        }
    }
}
