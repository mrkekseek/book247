<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CashTerminal extends Model
{
    protected $table = 'cash_terminals';

    public static $attributeNames = array(
        'name'          => 'Terminal Name',
        'bar_code'      => 'Bar Code',
        'location_id'   => 'Location',
        'status'        => 'Terminal Status',
    );

    public static $message = array();

    protected $fillable = [
        'name',
        'bar_code',
        'location_id',
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
                    'name'          => 'required|unique:cash_terminals,name',
                    'bar_code'      => 'required|unique:cash_terminals,bar_code',
                    'location_id'   => 'required|exists:shop_locations,id',
                    'status'        => 'required|in:active,suspended,cancelled',
                ];
            }
            case 'PUT':
            case 'PATCH':
            {
                return [
                    'name'          => 'required|unique:cash_terminals,name'.($id ? "$id,id" : ''),
                    'bar_code'      => 'required|unique:cash_terminals,bar_code'.($id ? "$id,id" : ''),
                    'location_id'   => 'required|exists:shop_locations,id',
                    'status'        => 'required|in:active,suspended,cancelled',
                ];
            }
            default:break;
        }
    }

    public function last_cash_in(){
        return '1';
    }

    public function last_cash_out(){
        return '2';
    }
}
