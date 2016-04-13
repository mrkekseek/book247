<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShopResourceCategory extends Model
{
    protected $table = 'shop_resource_categories';
    protected $primaryKey = 'id';

    public static $attributeNames = array(
        'name' => 'Shop Name',
        'url' => 'Bank Account Number',
    );
    public static $validationMessages = array(

    );

    protected $fillable = array(
        'name',
        'url',
    );

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
                    'name'  => 'required|min:5|max:75|unique:shop_resource_categories,name',
                    'url'   => 'required|min:5|max:75|unique:shop_resource_categories,url',
                ];
            }
            case 'PUT':
            case 'PATCH':
            {
                return [
                    'name'  => 'required|min:5|max:75|unique:shop_resource_categories,name'.($id ? ','.$id.',id' : ''),
                    'url'   => 'required|min:5|max:75|unique:shop_resource_categories,url'.($id ? ','.$id.',id' : ''),
                ];
            }
            default:break;
        }
    }
}
