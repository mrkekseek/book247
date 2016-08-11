<?php

namespace App\Http\Controllers;

use App\Invoice;
use App\InvoiceItem;
use Illuminate\Http\Request;
use Auth;
use App\User;

use App\Http\Requests;
use Webpatser\Countries\Countries;

class InvoiceController extends Controller
{
    public function list_all_invoices(){

    }

    public function view_invoice($id){
        if (!Auth::check()) {
            return redirect()->intended(route('admin/login'));
        }
        else{
            $user = Auth::user();
        }

        $invoice = Invoice::where('invoice_number','=',$id)->get()->first();
        if ($invoice){
            $subtotal = 0;
            $total = 0;
            $discount = 0;
            $vat = [];

            $items = InvoiceItem::where('invoice_id','=',$invoice->id)->get();
            foreach($items as $item){
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
                $subtotal+= $item_one_price * $item->quantity;
                $total+= ($item_one_price + $item_vat)*$item->quantity;
            }

            $member = User::with('ProfessionalDetail')->with('PersonalDetail')->where('id','=',$invoice->user_id)->get()->first();
            $member_professional = $member->ProfessionalDetail;
            $member_personal = $member->PersonalDetail;
            //xdebug_var_dump($member_professional);
            //xdebug_var_dump($member_personal);
            //xdebug_var_dump($member);

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
                'date_of_birth' => $member_personal->date_of_birth,
                'country'   => $country,
            ];
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

        return view('admin/finance/view_invoice', [
            'breadcrumbs' => $breadcrumbs,
            'text_parts'  => $text_parts,
            'in_sidebar'  => $sidebar_link,
            'invoice'   => $invoice,
            'invoice_items' => @$items,
            'member'    => @$invoice_user,
            'sub_total' => $total,
            'discount' => $discount,
            'vat' => $vat,
            'grand_total' => $total
        ]);
    }

    public function edit_invoice($id){

    }

    public function update_invoice($id){

    }
}
