<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OptimizeSearchMembers extends Model
{
    protected $table = 'optimize_search_members';

    public static $attributeNames = array(
        'user_id'       => 'User ID',
        'first_name'    => 'First Name',
        'middle_name'   => 'Middle Name',
        'last_name'     => 'Last Name',

        'email'     => 'Email',
        'phone'     => 'Phone No.',

        'city'      => 'City',
        'region'    => 'Region',

        'membership_name'       => 'Membership Name',
        'user_profile_image'    => 'User Profile Image',
        'base64_avatar_image'   => 'Avatar Base64 Image',

        'user_link_details'     => 'User Link',
    );

    public static $message = array();

    protected $fillable = [
        'user_id',
        'first_name',
        'middle_name',
        'last_name',
        'email',
        'phone',
        'city',
        'region',
        'membership_name',
        'user_profile_image',
        'base64_avatar_image',
        'user_link_details'
    ];

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
                    'user_id',
                    'first_name',
                    'middle_name',
                    'last_name',
                    'email',
                    'phone',
                    'city',
                    'region',
                    'membership_name',
                    'user_profile_image',
                    'base64_avatar_image',
                    'user_link_details',

                    'shop_location_id'  => 'required|exists:shop_locations,id',
                    'var_name'  => 'required|min:3',
                    'var_value' => 'required|min:1',
                ];
            }
            case 'PUT':
            case 'PATCH':
            {
                return [
                    'shop_location_id'  => 'required|exists:shop_locations,id',
                    'var_name'  => 'required|min:3',
                    'var_value' => 'required|min:1',
                ];
            }
            default:break;
        }
    }

    public static function reset_search_table(){

    }
}
