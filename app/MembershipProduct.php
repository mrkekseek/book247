<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MembershipProduct extends Model
{
    protected $table = 'membership_products';

    public static $attributeNames = array(
        'name'          => 'Setting Name',
        'color_code'    => 'System Internal Reference Name'
    );

    public static $validationMessages = array();

    protected $fillable = [
        'name',
        'color_code'
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
                    'name'          => 'required|unique:membership_products,name',
                    'color_code'    => 'required|size:7',
                ];
            }
            case 'PUT':
            case 'PATCH':
            {
                return [
                    'name'          => 'required|unique:membership_products,name'.($id ? ",$id,id" : ''),
                    'color_code'    => 'required|size:7',
                ];
            }
            default:break;
        }
    }
}
