<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserAccessCard extends Model
{
    protected $table = 'user_access_cards';

    public static $attributeNames = array(
        'user_id'   => 'User ID',
        'key_no'    => 'Key Number',
        'status'    => 'Key Status',
    );

    public static $message = array();

    protected $fillable = [
        'user_id',
        'key_no',
        'status',
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
                    'user_id'   => 'required|exists:users,id',
                    'key_no'    => 'required|unique:user_access_cards,key_no',
                    'status'    => 'required|in:active,old'
                ];
            }
            case 'PUT':
            case 'PATCH':
            {
                return [
                    'user_id'   => 'required|exists:users,id',
                    'key_no'    => 'required|unique:user_access_cards,key_no'.($id ? ",$id,id" : ''),
                    'status'    => 'required|in:active,old'
                ];
            }
            default:break;
        }
    }
}
