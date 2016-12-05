<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Invoice;
use App\UserMembership;

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

    public function issue_invoice(){
        if ($this->status=='old'){
            return [
                'success'   => false,
                'title'     => 'Error invoicing',
                'errors'    => 'Invoice already issued'
            ];
        }

        $userMembershipPlan = UserMembership::where('id','=',$this->user_membership_id)->where('status','=','active')->get()->first();
        if (!$userMembershipPlan){
            return [
                'success'   => false,
                'title'     => 'No User Membership',
                'errors'    => 'No active user membership plan found'
            ];
        }

        $firstMembershipPlannedInvoice = UserMembershipInvoicePlanning::where('user_membership_id','=',$this->user_membership_id)->orderBy('issued_date','ASC')->get()->first();
        $firstMembershipIssuedInvoice = Invoice::where('id','=',$firstMembershipPlannedInvoice->invoice_id)->get()->first();

        $member_invoice = new Invoice();
        $member_invoice->user_id        = $firstMembershipIssuedInvoice->user_id;
        $member_invoice->employee_id    = $firstMembershipIssuedInvoice->employee_id;
        $member_invoice->invoice_type   = 'membership_plan_invoice';
        $member_invoice->invoice_reference_id = $this->user_membership_id;
        $member_invoice->invoice_number = Invoice::next_invoice_number();
        $member_invoice->status     = 'pending';
        $member_invoice->save();

        $invoice_item = [
            'item_name'         => $this->item_name,
            'item_type'         => 'user_memberships',
            'item_reference_id' => $this->user_membership_id,
            'quantity'          => 1,
            'price'             => $this->price,
            'vat'               => 0,
            'discount'          => $this->discount
        ];
        $member_invoice->add_invoice_item($invoice_item);

        // we update the planned invoice status to old + we add the id to the issued invoice to it
        $this->status = 'old';
        $this->invoice_id = $member_invoice->id;
        $this->save();
        return [
            'success'   => true,
            'title'     => 'Invoice Issued',
            'message'   => 'All went well. Invoice issued'
        ];
    }
}
