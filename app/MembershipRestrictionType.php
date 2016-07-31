<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MembershipRestrictionType extends Model
{
    protected $table = 'membership_restriction_types';

    public static $attributeNames = array(
        'name'  => 'Name',
        'title' => 'Restriction Title'
    );

    public static $message = array();

    protected $fillable = [
        'name',
        'title'
    ];

    public static function rules($method, $id=0){
        switch($method) {
            case 'GET':
            case 'DELETE': {
                return [];
            }
            case 'POST': {
                return [
                    'name'  => 'required|min:3|max:75|unique:membership_restriction_types,name',
                    'title' => 'required|min:3|max:75|unique:membership_restriction_types,title',
                ];
            }
            case 'PUT':
            case 'PATCH':{
                return [
                    'name'  => 'required|min:3|max:75|unique:membership_restriction_types,name'.($id ? ",$id,id" : ''),
                    'title' => 'required|min:3|max:75|unique:membership_restriction_types,title'.($id ? ",$id,id" : ''),
                ];
            }
            default:break;
        }
    }
}
