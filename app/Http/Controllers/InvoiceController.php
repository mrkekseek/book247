<?php

namespace App\Http\Controllers;

use App\Invoice;
use App\Address;
use App\InvoiceFinancialTransaction;
use App\InvoiceItem;
use Illuminate\Http\Request;
use Auth;
use App\User;

use App\Http\Requests;
use Regulus\ActivityLog\Models\Activity;
use Webpatser\Countries\Countries;

class InvoiceController extends Controller
{
    public function list_all_invoices(){

    }

    public function view_invoice($id){
        $user = Auth::user();
        if (!$user || !$user->is_back_user()) {
            return redirect()->intended(route('admin/login'));
        }

        $invoice = Invoice::with('transactions')->with('items')->where('invoice_number','=',$id)->get()->first();
        if ($invoice){
            $subtotal = 0;
            $total = 0;
            $discount = 0;
            $vat = [];

            $itemNames = [];
            foreach($invoice->items as $item){
                // base price minus the discount
                $item_one_price = $item->price - (($item->price*$item->discount)/100);
                // apply the vat to the price
                $item_vat = $item_one_price * ($item->vat/100);

                if (isset($vat[$item->vat])){
                    $vat[$item->vat]+= $item_vat*$item->quantity;
                }
                else{
                    $vat[$item->vat] = $item_vat*$item->quantity;
                }

                $discount+= (($item->price*$item->discount)/100)*$item->quantity;
                $subtotal+= $item->price * $item->quantity;
                $total+= ($item_one_price + $item_vat)*$item->quantity;

                $itemNames[$item->id] = $item->item_name;
            }

            foreach($invoice->transactions as &$transaction){
                $innerItems = json_decode($transaction->invoice_items);
                $transactionItemNames = [];
                if ($innerItems) {
                    foreach($innerItems as $single){
                        $transactionItemNames[] = $itemNames[$single];
                    }
                }

                $transaction->names = $transactionItemNames;
            }

            $member = User::with('ProfessionalDetail')->with('PersonalDetail')->where('id','=',$invoice->user_id)->get()->first();
            $member_personal = $member->PersonalDetail;

            if ($member->country_id==0){
                $country = '-';
            }
            else{
                $get_country = Countries::where('id','=',$member->country_id)->get()->first();
                $country = $get_country->name;
            }

            $address = '';
            if (isset($member_personal->address) && $member_personal->address) {
                $address = Address::find($member_personal->address);
                if (isset($address->country_id) && $address->country_id==0) {
                    $country = '-';
                } elseif (isset($address->country_id)) {
                    $get_country = Countries::where('id','=',$address->country_id)->get()->first();
                    $country = $get_country->name;
                }
            }

            $invoice_user = [
                'full_name' => @$member->first_name.' '.@$member->middle_name.' '.@$member->last_name,
                'email_address' => @$member->email,
                'date_of_birth' => @$member_personal->date_of_birth,
                'country'   => @$country,
                'address'   => $address
            ];

            $transactions = $invoice->transactions;
        }
        else{
            // error page
        }

        $breadcrumbs = [
            'Home'              => route('admin'),
            'Administration'    => route('admin'),
            'Back End User'     => route('admin'),
            'All Backend Users' => '',
        ];
        $text_parts  = [
            'title'     => 'Back-End Users',
            'subtitle'  => 'view all users',
            'table_head_text1' => 'Backend User List'
        ];
        $sidebar_link= 'admin-backend-shop-new_order';

        $payee = json_decode($invoice->payee_info);

        if (isset($payee->country_id) && $payee->country_id == 0) {
            $country = '-';
            $currency = '' ;
        } else {
            $get_country = Countries::where('id', '=', $member->country_id)->get()->first();
            $country = $get_country->name;
            $currency = $get_country->currency_code;
        }


        return view('admin/finance/view_invoice', [
            'breadcrumbs'   => $breadcrumbs,
            'text_parts'    => $text_parts,
            'in_sidebar'    => $sidebar_link,
            'invoice'       => $invoice,
            'invoice_items' => @$invoice->items,
            'member'        => @$invoice_user,
            'sub_total'     => $subtotal,
            'discount'      => $discount,
            'vat'           => $vat,
            'grand_total'   => $total,
            'financialTransactions' => $transactions,
            'financial_profile' => json_decode($invoice->payee_info),
            'country' => $country,
            'currency' => $currency
        ]);
    }

    public function edit_invoice($id){

    }

    public function update_invoice($id){

    }

    public function mark_as_paid(Request $request){
        $user = Auth::user();
        if (!$user || !$user->is_back_user()) {
            return [
                'success' => false,
                'title'   => 'You need to be logged in',
                'errors'  => 'This function is available to logged users only'];
        }

        $vars = $request->only('invoice_number','method','other_details');
        $invoice = Invoice::where('invoice_number','=',$vars['invoice_number'])->get();

        if (sizeof($invoice)!=1){
            // return error - something is wrong

        }
        else{
            $invoice = $invoice[0];
            // make manual payment
            switch ($vars['method']){
                case 'credit' :
                    $otherDetails = 'Backend - paid with store credit';
                    // we check if the amount of store credit that is needed for this transaction is available
                    $member = User::where('id','=',$invoice->user_id)->get()->first();
                    if (!$member){
                        return [
                            'success'   => false,
                            'errors'    => 'Could not find invoiced member'];
                    }

                    $totalAmount = $invoice->get_outstanding_amount();
                    $availableStoreCredit = $member->get_available_store_credit();

                    if ($availableStoreCredit<$totalAmount){
                        return [
                            'success' => false,
                            'errors' => 'Not enough store credit.'];
                    }
                    else{
                        $member->spend_store_credit($invoice->id, $totalAmount);
                    }
                    break;
                case 'card' :
                    $otherDetails = 'Backend - manual credit card payment';
                    break;
                case 'cash' :
                    $otherDetails = 'Backend - manual cash payment';
                    break;
                default:
                    return [
                        'success' => false,
                        'title'   => 'You need to be logged in',
                        'errors'  => 'This function is available to logged users only'];
                    break;
            }

            if (!isset($vars['status'])){
                // manual payment at the store/location, so status is finished
                $vars['status'] = 'completed';
            }

            $message = $invoice->add_transaction($user->id, $vars['method'], $vars['status'], $otherDetails );
            if ($message['success']==true){
                // check if invoice is all paid
                $invoice->check_if_paid();
                // if invoice type is booking_invoice then we check if all paid bookings are marked as paid
                if ($invoice->invoice_type=="booking_invoice"){
                    $invoice->mark_bookings_as_paid($user->id);
                }

                Activity::log([
                    'contentId'     => $user->id,
                    'contentType'   => 'booking_invoices',
                    'action'        => 'Invoice transaction update',
                    'description'   => 'New transaction recorded for the invoice',
                    'details'       => 'User Email : '.$user->email,
                    'updated'       => true,
                ]);

                return [
                    'success' => true,
                    'message' => 'Transaction successfully registered.'];
            }
            else{
                // something went wrong and we need to give the store credit back
                return [
                    'success' => false,
                    'errors' => $message['errors']];
            }
        }
    }
}