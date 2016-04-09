<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CashTerminalDailyReport extends Model
{
    protected $table = 'cash_terminal_daily_reports';

    public static $attributeNames = [
        'terminal_id'   => 'Location',
        'user_id'       => 'Location',
        'cash_out'      => 'Terminal Name',
        'cash_left'     => 'Bar Code',
        'type'          => 'Terminal Status',
    ];

    public static $message = array();

    protected $fillable = [
        'terminal_id',
        'user_id',
        'cash_out',
        'cash_left',
        'type',
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
                    'terminal_id'   => 'required|exists:cash_terminals,id',
                    'user_id'       => 'required|exists:users,id',
                    'cash_out'      => 'required|numeric',
                    'cash_left'     => 'required|numeric',
                    'type'          => 'required|in:shift_start,shift_end',
                ];
            }
            case 'PUT':
            case 'PATCH':
            {
                return [
                    'terminal_id'   => 'required|exists:cash_terminals,id',
                    'user_id'       => 'required|exists:users,id',
                    'cash_out'      => 'required|numeric',
                    'cash_left'     => 'required|numeric',
                    'type'          => 'required|in:shift_start,shift_end',
                ];
            }
            default:break;
        }
    }
}
