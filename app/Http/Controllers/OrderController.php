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
}
