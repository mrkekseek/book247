<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductAvailability extends Model
{
    protected $table = 'product_availabilities';

    public static $attributeNames = array(
        'product_id' => 'Product ID',
        'available_from' => 'Start Date',
        'available_to'   => 'End Date',
    );

    public static $message = array();

    protected $fillable = [
        'product_id',
        'available_from',
        'available_to'
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
                    'product_id'     => 'required|exists:products,id',
                    'available_from' => 'required|date',
                    'available_to'   => 'required|date|after:available_from'
                ];
            }
            case 'PUT':
            case 'PATCH':
            {
                return [
                    'product_id'     => 'required|exists:products,id',
                    'available_from' => 'required|date',
                    'available_to'   => 'required|date|after:available_from'
                ];
            }
            default:break;
        }
    }

    public function product(){
        return $this->belongsTo('App\Product', 'product_id', 'id');
    }
}
