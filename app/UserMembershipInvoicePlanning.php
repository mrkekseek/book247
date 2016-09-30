<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserMembershipInvoicePlanning extends Model
{
    protected $table = 'user_membership_invoice_planning';

    public static $attributeNames = array(
        'user_membership_id'=> 'User Membership ID',
        'item_name'         => 'Item name',
        'price'             => 'Price',
        'discount'          => 'Discount',
        'issued_date'       => 'Issued Date',
        'status'            => 'Status',
        'last_active_date'  => 'Last Active Date'
    );

    public static $message = array();

    protected $fillable = [
        'user_membership_id',
        'item_name',
        'price',
        'discount',
        'issued_date',
        'status',
        'last_active_date'
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
                    'user_membership_id'    => 'required|exists:user_memberships,id',
                    'item_name'             => 'required|min:3',
                    'price'                 => 'required|numeric',
                    'discount'              => 'numeric',
                    'issued_date'           => 'date',
                    'last_active_date'      => 'date',
                    'status'                => 'required|in:old,pending,last',
                ];
            }
            case 'PUT':
            case 'PATCH':
            {
                return [
                    'user_membership_id'    => 'required|exists:user_memberships,id',
                    'item_name'             => 'required|min:3',
                    'price'                 => 'required|numeric',
                    'discount'              => 'numeric',
                    'issued_date'           => 'date',
                    'last_active_date'      => 'date',
                    'invoice_id'            => 'required|exists:invoices,id|unique',
                    'status'                => 'required|in:old,pending,last',
                ];
            }
            default:break;
        }
    }
}
