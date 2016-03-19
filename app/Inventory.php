<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    protected $table = 'inventories';

    public static $attributeNames = array(
        'product_id' => 'Product Name',
        'location_id' => 'Alternate Name',
        'quantity' => 'Category',
        'entry_price' => 'Product Brand',
        'user_id' => 'Product Logo'
    );

    public static $message = array();

    protected $fillable = [
        'product_id',
        'location_id',
        'quantity',
        'entry_price',
        'user_id'
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
                    'product_id'    => 'required|exists:products,id',
                    'location_id'   => 'required|exists:shop_locations,id',
                    'quantity'      => 'required|min:1|integer',
                    'entry_price'   => 'required|numeric',
                    'user_id'       => 'required|exists:users,id',
                ];
            }
            case 'PUT': {
                return [];
            }
            case 'PATCH':
            {
                return [
                    'product_id'    => 'required|exists:products,id',
                    'old_location_id'   => 'required|exists:shop_locations,id',
                    'new_location_id'   => 'required|exists:shop_locations,id',
                    'quantity'      => 'required|min:1|integer',
                    'entry_price'   => 'required|numeric',
                    'user_id'       => 'required|exists:users,id',
                ];
            }
            default:break;
        }
    }
}
