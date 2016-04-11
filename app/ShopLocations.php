<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShopLocations extends Model
{
    protected $table = 'shop_locations';
    protected $primaryKey = 'id';

    public static $attributeNames = array(
        'name' => 'Shop Name',
        'bank_acc_no' => 'Bank Account Number',
        'phone' => 'Phone Number',
        'fax' => 'Fax Number',
        'email' => 'Contact Email',
        'registered_no' => 'Registered Number',
    );
    public static $validationMessages = array(
        'registered_no.unique' => 'Duplicate registration number in the database',
    );

    protected $fillable = array(
        'name',
        'address_id',
        'bank_acc_no',
        'phone',
        'fax',
        'email',
        'registered_no',
    );

    public function address(){
        return $this->hasOne('App\Address');
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
                    'name' => 'required|min:5|max:50|unique:shop_locations',
                    'bank_acc_no' => 'required|min:5',
                    'phone' => 'required|min:5',
                    'fax' => 'required|min:5',
                    'email' => 'required|email:true',
                    'registered_no' => 'required|min:5|unique:shop_locations',
                ];
            }
            case 'PUT':
            case 'PATCH':
            {
                return [
                    'name' => 'required|min:5|max:50|unique:shop_locations,name'.($id ? ','.$id.',id' : ''),
                    'bank_acc_no' => 'required|min:5',
                    'phone' => 'required|min:5',
                    'fax' => 'required|min:5',
                    'email' => 'required|email:true',
                    'registered_no' => 'required|min:5|unique:shop_locations,registered_no'.($id ? ','.$id.',id' : ''),
                ];
            }
            default:break;
        }
    }
}
