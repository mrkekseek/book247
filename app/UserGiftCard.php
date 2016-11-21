<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserGiftCard extends Model
{
    protected $table = 'user_gift_cards';

    public static $attributeNames = array(
        'user_id'       => 'User ID',
        'employee_id'   => 'Employee ID',
        'value'         => 'Value : positive or negative integer',
        'description'   => 'Description',
        'status'        => 'Gift Card Status',
    );

    public static $message = array();

    protected $fillable = [
        'user_id',
        'employee_id',
        'value',
        'description',
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
                    'user_id'       => 'required|exists:users,id',
                    'employee_id'   => 'required|exists:users,id',
                    'value'         => 'required|numeric|min:0',
                    'description'   => 'required|min:2|max:250',
                    'status'        => 'required|in:active,old',
                ];
            }
            case 'PUT':
            case 'PATCH':
            {
                return [
                    'user_id'       => 'required|exists:users,id',
                    'employee_id'   => 'required|exists:users,id',
                    'value'         => 'required|numeric|min:0',
                    'description'   => 'required|min:2|max:250',
                    'status'        => 'required|in:active,old',
                ];
            }
            default:break;
        }
    }
}
