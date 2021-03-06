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

    public function get_list_price(){
        $price = ProductPrice::with('currency')->where('product_id','=',$this->id)->orderBy('updated_at','desc')->get()->first();
        return $price;
    }

    public function get_entry_price(){
        $price = Inventory::select('entry_price','created_at')->where('product_id','=',$this->id)->orderBy('created_at','desc')->get()->first();
        if ($price) {
            $abc = $price->created_at;
            $price->added_on = $abc->format('d-m-Y');
        }
        return $price;
    }

    public function get_vat(){
        $vat = Product::with('vat_rate')->where('id','=',$this->id)->get()->first();
        return $vat->vat_rate;
    }

    public function category(){
        return $this->belongsTo('App\ProductCategories', 'category_id', 'id');
    }

    public function vat_rate(){
        return $this->belongsTo('App\VatRate', 'vat_rate_id', 'id');
    }

    public function availability(){
        return $this->hasMany('App\ProductAvailability', 'product_id', 'id');
    }

    public function images(){
        return $this->hasMany('App\ProductImage', 'product_id', 'id');
    }

    public function documents(){
        return $this->hasMany('App\ProductDocument', 'product_id', 'id');
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
                    'name'      => 'required|min:3|max:75|unique:products,name',
                    'category_id'   => 'required|exists:product_categories,id',
                    'brand'     => 'required|min:3',
                    'vat_rate_id'   => 'required|exists:vat_rates,id',
                    'url'       => 'required|min:3|unique:products,url',
                    'status'    => 'required|in:0,1'
                ];
            }
            case 'PUT':
            case 'PATCH':
            {
                return [
                    'name'      => 'required|min:3|max:75|unique:products,name'.($id ? ",$id,id" : ''),
                    'category_id'   => 'required|exists:product_categories,id',
                    'brand'     => 'required|min:3',
                    'vat_rate_id'   => 'required|exists:vat_rates,id',
                    'url'       => 'required|min:3|unique:products,url'.($id ? ",$id,id" : ''),
                    'status'    => 'required|in:0,1'
                ];
            }
            default:break;
        }
    }
}
