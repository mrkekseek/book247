<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ShopLocations;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\User;
use Auth;

class OrderController extends Controller
{
    public function __construct()
    {
        //$this->middleware('auth');
    }

    public function add_order(){
        if (!Auth::check()) {
            return redirect()->intended(route('admin/login'));
        }

        $shops = ShopLocations::all();

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
        $sidebar_link= 'admin-backend-shop-products-list';

        return view('admin/shops/add_new_order', [
            'shops'       => $shops,
            'breadcrumbs' => $breadcrumbs,
            'text_parts'  => $text_parts,
            'in_sidebar'  => $sidebar_link,
        ]);
    }

    public function get_order_details(Request $request, $id=-1){

        $order_details = [
            'order_no' => ' 12313232 <span class="label label-info label-sm"> Email confirmation was sent </span> ',
            'order_date_time' => ' Dec 27, 2013 7:16:25 PM ',
            'order_status' => ' <span class="label label-success"> Closed </span> ',
            'order_total_price' => ' $175.25 ',
            'order_payment_info' => ' Credit Card ',
        ];

        return $order_details;
    }
}
