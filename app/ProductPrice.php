<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductPrice extends Model
{
    protected $table = 'product_prices';

    public static $attributeNames = array(
        'product_id' => 'Product Name',
        'list_price' => 'List Price',
        'country_id' => 'Category',
        'start_date' => 'Start Date',
        'end_date'   => 'End Date',
    );

    public static $message = array();

    protected $fillable = [
        'product_id',
        'list_price',
        'country_id',
        'start_date',
        'end_date',
    ];

    public function currency(){
        return $this->belongsTo('Webpatser\Countries\Countries', 'country_id', 'id');
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
                    'product_id'    => 'required|exists:products,id',
                    'list_price'    => 'required|numeric',
                    'country_id'    => 'required|exists:countries,id',
                    'start_date'    => 'required|date',
                ];
            }
            case 'PUT':
            case 'PATCH':
            {
                return [
                    'product_id'    => 'required|exists:products,id',
                    'list_price'    => 'required|numeric',
                    'country_id'    => 'required|exists:countries,id',
                    'start_date'    => 'required|date',
                ];
            }
            default:break;
        }
    }
}
