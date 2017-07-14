<?php

namespace App\Http\Controllers\Federation;

use App\Http\Controllers\InvoiceController as Base;
use App\Http\Libraries\Auth;
use App\User;
use Webpatser\Countries\Countries;
use App\Invoice;

class InvoiceController extends Base {

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
                foreach($innerItems as $single){
                    $transactionItemNames[] = $itemNames[$single];
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

            $invoice_user = [
                'full_name' => $member->first_name.' '.$member->middle_name.' '.$member->last_name,
                'email_address' => $member->email,
                'date_of_birth' => @$member_personal->date_of_birth,
                'country'   => $country,
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

        return view('admin/finance/federation/view_invoice', [
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
            'financialTransactions' => $transactions
        ]);
    }

}