<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    protected $table = 'invoice_items';

    public static $attributeNames = array(
        'invoice_id'    => 'Invoice ID',
        'item_name'     => 'Item Name',
        'item_type'     => 'Item Type',
        'item_reference_id' => 'Item Reference ID',
        'quantity'      => 'Item Quantity',
        'price'         => 'Item Price',
        'discount'      => 'Item Discount',
        'vat'           => 'Item VAT',
        'total_price'   => 'Total Line Value',
    );

    public static $message = array();

    protected $fillable = [
        'invoice_id',
        'item_name',
        'item_type',
        'item_reference_id',
        'quantity',
        'price',
        'discount',
        'vat',
        'total_price',
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
                    'invoice_id'    => 'required|exists:invoices,id',
                    'item_name'     => 'required|string|between:2,150',
                    'item_type'     => 'required|string|between:2,150',
                    'item_reference_id' => 'required|integer',
                    'quantity'  => 'required|integer|min:0',
                    'price'     => 'required|numeric|min:0',
                    'discount'  => 'required|numeric|min:0|max:100',
                    'vat'       => 'required|numeric|min:0|max:100',
                    'total_price'    => 'required|numeric|min:0',
                ];
            }
            case 'PUT':
            case 'PATCH':
            {
                return [
                    'invoice_id'    => 'required|exists:invoices,id',
                    'item_name'     => 'required|string|between:2,150',
                    'item_type'     => 'required|string|between:2,150',
                    'item_reference_id' => 'required|integer',
                    'quantity'  => 'required|integer|min:0',
                    'price'     => 'required|numeric|min:0',
                    'discount'  => 'required|numeric|min:0|max:100',
                    'vat'       => 'required|numeric|min:0|max:100',
                    'total_price'    => 'required|numeric|min:0',
                ];
            }
            default:break;
        }
    }

    public function invoice(){
        return $this->belongsTo('App\Invoices');
    }
}
