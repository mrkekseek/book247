<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShopResource extends Model
{
    protected $table = 'shop_resources';
    protected $primaryKey = 'id';

    public static $attributeNames = array(
        'location_id' => 'Shop Name',
        'name' => 'Resource Name',
        'description' => 'Description',
        'category_id' => 'Category ID',
        'color_code' => 'Color Code'
    );
    public static $validationMessages = array(

    );

    protected $fillable = array(
        'location_id',
        'name',
        'description',
        'category_id',
        'color_code',
    );

    public function shop_location(){
        return $this->hasOne('App\ShopLocation', 'id', 'location_id');
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
                    'location_id'   => 'exists|shop_locations:id',
                    'name' => 'required|min:5|max:75|unique:shop_resources,name',
                    'category_id' => 'exists|shop_resource_categories:id',
                ];
            }
            case 'PUT':
            case 'PATCH':
            {
                return [
                    'location_id'   => 'exists|shop_locations:id',
                    'name' => 'required|min:5|max:75|unique:shop_resources,name'.($id ? ','.$id.',id' : ''),
                    'category_id' => 'exists|shop_resource_categories:id',
                ];
            }
            default:break;
        }
    }
}
