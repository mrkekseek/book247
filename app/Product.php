<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';

    public static $attributeNames = array(
        'name' => 'Product Name',
        'alternate_name' => 'Alternate Name',
        'category_id' => 'Category',
        'brand' => 'Product Brand',
        'logo' => 'Product Logo',
        'manufacturer' => 'Manufacturer Name',
        'description' => 'Product Description',
        'url' => 'Product URL',
        'barcode' => 'Product Bar Code',
        'status' => 'Status',
        'vat_rate_id' => 'VAT Rate',
    );

    public static $message = array();

    protected $fillable = [
        'name',
        'alternate_name',
        'category_id',
        'brand',
        'color',
        'logo',
        'manufacturer',
        'description',
        'url',
        'barcode',
        'status',
        'vat_rate_id'
    ];

    public function category(){
        return $this->belongsTo('App\ProductCategories', 'category_id', 'id');
    }

    public function vat_rate(){
        return $this->belongsTo('App\VatRate', 'vat_rate_id', 'id');
    }

    public function availability(){
        return $this->hasMany('App\ProductAvailability', 'product_id', 'id');
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
                    'name'      => 'required|min:3|max:75|unique:roles',
                    'category_id'   => 'required|exists:product_categories,id',
                    'brand'     => 'required|min:3',
                    'vat_rate_id'   => 'required|exists:vat_rates,id',
                    'url'       => 'required|min:3|unique:products,url',
                    'status'    => 'required|in_array:0,1'
                ];
            }
            case 'PUT':
            case 'PATCH':
            {
                return [
                    'name'      => 'required|min:3|max:75|unique:roles,'.($id ? "id, $id," : ''),
                    'category_id'   => 'required|exists:product_categories,id',
                    'brand'     => 'required|min:3',
                    'vat_rate_id'   => 'required|exists:vat_rates,id',
                    'url'       => 'required|min:3|unique:products,url,'.($id ? "id, $id," : ''),
                    'status'    => 'required|in_array:0,1'
                ];
            }
            default:break;
        }
    }
}
