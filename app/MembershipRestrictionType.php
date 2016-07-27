<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MembershipRestrictionType extends Model
{
    protected $table = 'membership_restriction_types';

    public static $attributeNames = array(
        'name'     => 'Name'
    );

    public static $message = array();

    protected $fillable = [
        'name'
    ];

    public static function rules($method, $id=0){
        switch($method) {
            case 'GET':
            case 'DELETE': {
                return [];
            }
            case 'POST': {
                return [
                    'name' => 'required|min:3|max:75|unique:membership_restriction_types,name',
                ];
            }
            case 'PUT':
            case 'PATCH':{
                return [
                    'name' => 'required|min:3|max:75|unique:membership_restriction_types,name'.($id ? ",$id,id" : ''),
                ];
            }
            default:break;
        }
    }
}
