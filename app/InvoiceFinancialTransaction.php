<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InvoiceFinancialTransaction extends Model
{
    protected $table = 'invoice_financial_transactions';

    public static $attributeNames = array(
        'user_id'               => 'User ID',
        'invoice_id'            => 'Invoice Number',
        'invoice_items'         => 'Invoice Item/Items',
        'transaction_amount'    => 'Transaction Amount',
        'transaction_currency'  => 'Transaction Currency',
        'transaction_type'      => 'Transaction Type',
        'transaction_date'      => 'Transaction Date',
        'other_details'         => 'Invoice Details',
        'status'                => 'Invoice Status',
        //        'transaction_details',
    );

    public static $message = array();

    protected $fillable = [
        'user_id',
        'invoice_id',
        'invoice_items',
        'transaction_amount',
        'transaction_currency',
        'transaction_type',
        'transaction_date',
        'other_details',
//        'transaction_details',
        'status'
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
                    'user_id'               => 'required|exists:users,id',
                    'invoice_id'            => 'required|exists:invoices,id',
                    'transaction_amount'    => 'required|numeric',
                    'transaction_currency'  => 'required',
                    'transaction_type'      => 'required|in:cash,card,credit,manual',
                    'transaction_date'      => 'required|date',
                    'status'                => 'required|in:pending,processing,completed,cancelled,declined,incomplete',
                ];
            }
            case 'PUT':
            case 'PATCH':
            {
                return [
                    'user_id'               => 'required|exists:users,id',
                    'booking_invoice_id'    => 'required|exists:booking_invoices,id',
                    'transaction_amount'    => 'required|numeric',
                    'transaction_currency'  => 'required',
                    'transaction_type'      => 'required|in:cash,card,credit,manual',
                    'transaction_date'      => 'required|date',
                    'status'                => 'required|in:pending,processing,completed,cancelled,declined,incomplete',
                ];
            }
            default:break;
        }
    }

    public function invoice(){
        $this->belongsTo('App\Invoice', 'invoice_id', 'id');
    }
}
