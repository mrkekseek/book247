<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserFriends extends Model
{
    protected $table = 'user_friends';
    protected $primaryKey = 'id';

    public static $attributeNames = array(
        'user_id'   => 'User ID',
        'friend_id' => 'Friend ID',
        'status'    => 'Action Status'
    );

    public static $message = array();

    protected $fillable = array(
        'user_id',
        'friend_id',
        'status'
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
                    'user_id'   => 'required|exists:users,id',
                    'friend_id' => 'required|exists:users,id',
                    'status'    => 'required|in:active,pending'
                ];
            }
            case 'PUT':
            case 'PATCH':
            {
                return [
                    'user_id'   => 'required|exists:users,id',
                    'friend_id' => 'required|exists:users,id',
                    'status'    => 'required|in:active,pending,blocked'
                ];
            }
            default:break;
        }
    }
}
