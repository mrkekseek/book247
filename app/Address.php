<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    public static $attributeNames = array(
        'address1'    => 'Address Line 1',
        'address2'    => 'Address Line 2',
        'city'        => 'City',
        'region'      => 'Region',
        'postal_code' => 'Postal Code',
        'country_id'  => 'Country'
    );
    public static $validationMessages = array( );

    protected $fillable = [
        'user_id',
        'address1',
        'address2',
        'city',
        'region',
        'postal_code',
        'country_id',
    ];

    public function users(){
        return $this->belongsTo('App\User');
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
                    'address1'    => 'required|min:5|max:150',
                    'city'        => 'required|min:3|max:150',
                    'region'      => 'required|min:2',
                    'postal_code' => 'required|min:2',
                    'country_id'  => 'required|exists:countries,id'
                ];
            }
            case 'PUT':
            case 'PATCH':
            {
                return [
                    'address1'    => 'required|min:5|max:150',
                    'city'        => 'required|min:3|max:150',
                    'region'      => 'required|min:2',
                    'postal_code' => 'required|min:2',
                    'country_id'  => 'required|exists:countries,id'
                ];
            }
            default:break;
        }
    }
}
