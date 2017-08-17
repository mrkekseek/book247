<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\AppSettings;
use Carbon\Carbon;

class UserStoreCredits extends Model
{
    protected $table = 'user_store_credits';

    public static $attributeNames = array(
        'member_id'         => 'User ID',
        'back_user_id'      => 'Backend User ID',
        'title'             => 'Title for the pack',
        'value'             => 'Value',
        'total_amount'      => 'Active Amount',
        'invoice_id'        => 'Invoice ID',
        'expiration_date'   => 'Expiration Date',
        'status'            => 'Status',
    );

    public static $message = array();

    protected $fillable = [
        'member_id',
        'back_user_id',
        'title',
        'value',
        'total_amount',
        'invoice_id',
        'expiration_date',
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
                    'member_id'         => 'required|exists:users,id',
                    'back_user_id'      => 'required|exists:users,id',
                    'title'             => 'required|min:3',
                    'value'             => 'required|integer',
                    'total_amount'      => 'integer',
                    'invoice_id'        => 'exists:invoices,id',
                    'expiration_date'   => 'date',
                    'status'            => 'in:active,expired,deleted,spent,pending',
                ];
            }
            case 'PUT':
            case 'PATCH':
            {
                return [
                    'member_id'         => 'required|exists:users,id',
                    'back_user_id'      => 'required|min:3',
                    'title'             => 'required|min:3',
                    'value'             => 'required|integer',
                    'total_amount'      => 'integer',
                    'invoice_id'        => 'required|exists:invoices,id',
                    'expiration_date'   => 'date',
                    'status'            => 'in:active,expired,deleted,spent,pending',
                ];
            }
            default:break;
        }
    }

    public function activate_user_credits() {
        $credit_validity = AppSettings::get_setting_value_by_name('finance_store_credit_validity');
        if ($this->value>=0){
            $expiration_date = Carbon::today()->addMonthsNoOverflow($credit_validity)->format('Y-m-d');
        }
        else{
            $expiration_date  = Carbon::today()->format('Y-m-d');
        }
        $this->expiration_date = $expiration_date;
        $this->status = 'active';
        return $this->save();
    }
}
