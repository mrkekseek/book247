<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FinancialProfile extends Model
{
    protected $table = 'financial_profiles';
    protected $primaryKey = 'id';

    public static $message = array();
    public static $attributeNames = array(
        'profile_name'  => 'Profile Name',
        'company_name'  => 'Company Name',
        'bank_name'     => 'Bank Name',
        'bank_account'  => 'Bank Account',
        'organisation_number' => 'Organisation Number',
        'address1'      => 'Address1',
        'address2'      => 'Address2',
        'city'          => 'City',
        'postal_code'   => 'Postal_code',
        'region'        => 'Region',
        'country_id'    => 'Country',
        'is_default'    => 'Is Default'
    );

    protected $fillable = array(
        'profile_name',
        'company_name',
        'bank_name',
        'bank_account',
        'organisation_number',
        'address1',
        'address2',
        'city',
        'postal_code',
        'region',
        'country_id',
        'is_default'
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
                    'profile_name'  => 'required|min:3|unique:financial_profiles',
                    'company_name'  => 'required|min:3',
                    'bank_name'     => 'required|min:3',
                    'bank_account'  => 'required|min:5',
                    'organisation_number'  => 'required|min:3',
                    'address1'      => 'required|min:5',
                    'address2'      => '',
                    'city'          => 'required|min:2',
                    'postal_code'   => 'required|min:2',
                    'region'        => 'required|min:2',
                    'country_id'    => 'required|exists:countries,id',
                    'is_default'    => 'required|in:0,1'
                ];
            }
            case 'PUT':
            case 'PATCH':
            {
                return [
                    'profile_name'  => 'required|min:3|unique:financial_profiles,profile_name'.($id ? ','.$id.',id' : ''),
                    'company_name'  => 'required|min:3',
                    'bank_name'     => 'required|min:3',
                    'bank_account'  => 'required|min:3',
                    'organisation_number'  => 'required|min:3',
                    'address1'      => 'required|min:5',
                    'address2'      => '',
                    'city'          => 'required|min:2',
                    'postal_code'   => 'required|min:2',
                    'region'        => 'required|min:2',
                    'country_id'    => 'required|exists:countries,id',
                    'is_default'    => 'in:0,1'
                ];
            }
            default:break;
        }
    }

    public function country(){
        return $this->belongsTo('Webpatser\Countries\Countries', 'country_id', 'id');
    }
}
