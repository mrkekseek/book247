<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $table = 'invoices';

    public static $attributeNames = array(
        'employee_id'   => 'Creator ID',
        'invoice_type'  => 'Invoice Type',
        'invoice_reference_id'  => 'Reference ID',
        'invoice_number'=> 'Invoice Number',
        'other_details' => 'Invoice Details',
        'status'        => 'Invoice Status'
    );

    public static $message = array();

    protected $fillable = [
        'employee_id',
        'invoice_type',
        'invoice_reference_id',
        'invoice_number',
        'other_details',
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
                    'employee_id'           => 'required|exists:users,id',
                    'invoice_type'          => 'required|min:3',
                    'invoice_reference_id'  => 'required|numeric',
                    'invoice_number'        => 'required|unique:invoices,invoice_number',
                    'status'                => 'required|in:pending,ordered,processing,completed,cancelled,declined,incomplete,preordered',
                ];
            }
            case 'PUT':
            case 'PATCH':
            {
                return [
                    'employee_id'           => 'required|exists:users,id',
                    'invoice_type'          => 'required|min:3',
                    'invoice_reference_id'  => 'required|numeric',
                    'invoice_number'        => 'required|unique:invoices,invoice_number'.($id ? ",$id,id" : ''),
                    'status'                => 'required|in:pending,ordered,processing,completed,cancelled,declined,incomplete,preordered',
                ];
            }
            default:break;
        }
    }

    public function items(){
        return $this->hasMany('App\InvoiceItems', 'invoice_id', 'id')->orderBy('invoice_items.created_at','asc');
    }
}
