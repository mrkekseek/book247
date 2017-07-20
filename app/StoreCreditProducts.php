<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StoreCreditProducts extends Model
{
    protected $table = 'store_credit_products';

	public static $attributeNames = array(
        'name' => 'Service package name (package)',
        'description' => 'Short description',
        'store_credit_value' => 'Cost of store credit',
        'store_credit_price' => 'The cost of the package without a discount',
        'store_credit_discount_fixed' => 'Fixed price discount',
        'store_credit_discount_percentage' => 'Discount from price in %',
        'validity_days' => 'The relevance of the offer on certain days',
        'valid_from' => 'Date the package begins to operate',
        'valid_to' => 'Date the package begins to operate',
        'packages_per_user' => 'Count of packages that a user can purchase',
        'status' => 'Status',
        'added_by' => 'Issued by'
    );

    public static $message = array();

    protected $fillable = [
        'name',
        'description',
        'store_credit_value',
        'store_credit_price',
        'store_credit_discount_fixed',
        'store_credit_discount_percentage',
        'validity_days',
        'valid_from',
        'valid_to',
        'packages_per_user',
        'status',
        'added_by'
    ];

    public static function rules($method, $id = 0){
        switch($method){
            case 'GET':
            case 'DELETE':
            {
                return [];
            }
            case 'POST':
            {
                return [
                    'name'                  		        => 'required',
                    'description'   				        => 'required',
                    'store_credit_value'    		        => 'required|numeric',
                    'store_credit_price'              		=> 'required|numeric',
                    'store_credit_discount_fixed'           => 'required|numeric',
                    'store_credit_discount_percentage'      => 'required|numeric',
                    'validity_days'       					=> 'required|numeric',
                    'valid_from'   							=> 'required',
                    'valid_to' 								=> 'required',
                    'packages_per_user'     				=> 'required|numeric',
                    'status'      							=> 'required|in:active,pending,suspended,deleted',
                    'added_by'      						=> 'required|numeric'
                ];
            }
            case 'PUT':
            case 'PATCH':
            {
                return [
                    'name'                  		        => 'required',
                    'description'   				        => 'required',
                    'store_credit_value'    		        => 'required|numeric',
                    'store_credit_price'              		=> 'required|numeric',
                    'store_credit_discount_fixed'           => 'required|numeric',
                    'store_credit_discount_percentage'      => 'required|numeric',
                    'validity_days'       					=> 'required|numeric',
                    'valid_from'   							=> 'required',
                    'valid_to' 								=> 'required',
                    'packages_per_user'     				=> 'required|numeric',
                    'status'      							=> 'required|in:active,pending,suspended,deleted',
                    'added_by'      						=> 'required|numeric'
                ];
            }
            default:break;
        }
    }

    public function users()
    {
    	return $this->hasOne('App\User', 'id', 'added_by');
    }
}
