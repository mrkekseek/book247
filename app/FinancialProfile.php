<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FinancialProfile extends Model
{
    protected $table = 'financial_profile';
    protected $primaryKey = 'id';

    public static $attributeNames = array(
        'company_name'  => 'Company Name',
        'bank_name'     => 'Bank Name',
        'bank_account'  => 'Bank Account',
        'organisation_number' => 'Organisation Number',
        'address1'      => 'Address1',
        'address2'      => 'Address2',
        'city'          => 'City',
        'postal_code'   => 'Postal_code',
        'region'        => 'Region',
        'country'       => 'Country'
    );

    protected $fillable = array(
        'company_name',
        'bank_name',
        'bank_account',
        'organisation_number',
        'address1',
        'address2',
        'city',
        'postal_code',
        'region',
        'country',
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
                    'company_name'  => 'required',
                    'bank_name'     => 'required|min:5',
                    'bank_account'  => 'required|min:5',
                    'organisation_number'  => 'required|min:3',
                    'address1'      => 'required',
                    'address2'      => '',
                    'city'          => 'required',
                    'postal_code'   => 'required',
                    'region'        => 'required',
                    'country'       => 'required|exists:countries,id',
                ];
            }
            case 'PUT':
            case 'PATCH':
            {
                return [
                    'company_name'  => 'required',
                    'bank_name'     => 'required|min:5',
                    'bank_account'  => 'required|min:5',
                    'organisation_number'  => 'required|min:3',
                    'address1'      => 'required',
                    'address2'      => '',
                    'city'          => 'required',
                    'postal_code'   => 'required',
                    'region'        => 'required',
                    'country'       => 'required|exists:countries,id',
                ];
            }
            default:break;
        }
    }
}
