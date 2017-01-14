<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShopFinancialProfile extends Model
{
    protected $table = 'shop_financial_profiles';
    protected $primaryKey = 'id';

    public static $attributeNames = array(
        'shop_location_id'      => 'Shop Location Id',
        'financial_profile_id'  => 'Financial Profile',
    );

    protected $fillable = array(
        'shop_location_id',
        'financial_profile_id'
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
                    'shop_location_id'      => 'required|exists:shop_locations,id',
                    'financial_profile_id'  => 'required|exists:financial_profiles,id'
                ];
            }
            case 'PUT':
            case 'PATCH':
            {
                return [
                    'shop_location_id'      => 'required|exists:shop_locations,id',
                    'financial_profile_id'  => 'required|exists:financial_profiles,id'
                ];
            }
            default:break;
        }
    }
}
