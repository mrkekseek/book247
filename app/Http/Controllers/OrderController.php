<?php

namespace App\Http\Controllers;

use App\Order;
use App\OrderItem;
use App\Product;
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
            'orderID'     => -1
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

    public function get_order_lines_items(Request $request, $id=-1){
        $items = [  '<tr><td><a href="javascript:;"> Coca Cola Zero </a></td>
                    <td><span class="label label-sm label-success"> Available </span></td>
                    <td> 325.50$ </td>
                    <td> <input type="text" name="sell_price[]" value="345.50" class="form-control input-inline input-xsmall"><span class="help-inline">EUR</span> </td>
                    <td> <input type="text" name="quantity[]" class="form-control input-xsmall"> </td>
                    <td> 9% </td>
                    <td> 22.15 <span class="help-inline">EUR</span> </td>
                    <td> <input type="text" name="discount[]" class="form-control input-xsmall input-inline"><span class="help-inline">EUR</span> </td>
                    <td> 631.00$ </td></tr>',
                    '<tr><td><a href="javascript:;"> Coca Cola Zero </a></td>
                    <td><span class="label label-sm label-success"> Available </span></td>
                    <td> 345.50$ </td>
                    <td> <input type="text" name="sell_price[]" value="345.50" class="form-control input-inline input-xsmall"><span class="help-inline">EUR</span> </td>
                    <td> <input type="text" name="quantity[]" class="form-control input-xsmall"> </td>
                    <td> 9% </td>
                    <td> 22.15 <span class="help-inline">EUR</span> </td>
                    <td> <input type="text" name="discount[]" class="form-control input-xsmall input-inline"><span class="help-inline">EUR</span> </td>
                    <td> 591.00$ </td></tr>',
                    '<tr><td><a href="javascript:;"> Coca Cola Zero </a></td>
                    <td><span class="label label-sm label-success"> Available </span></td>
                    <td> 365.50$ </td>
                    <td> <input type="text" name="sell_price[]" value="345.50" class="form-control input-inline input-xsmall"><span class="help-inline">EUR</span> </td>
                    <td> <input type="text" name="quantity[]" class="form-control input-xsmall"> </td>
                    <td> 9% </td>
                    <td> 225.15 <span class="help-inline">EUR</span> </td>
                    <td> <input type="text" name="discount[]" class="form-control input-xsmall input-inline"><span class="help-inline">EUR</span> </td>
                    <td> 657.00$ </td></tr>',
                    '<tr><td><a href="javascript:;"> Coca Cola Zero </a></td>
                    <td><span class="label label-sm label-success"> Available </span></td>
                    <td> 349.50$ </td>
                    <td> <input type="text" name="sell_price[]" value="345.50" class="form-control input-inline input-xsmall"><span class="help-inline">EUR</span> </td>
                    <td> <input type="text" name="quantity[]" class="form-control input-xsmall"> </td>
                    <td> 9% </td>
                    <td> 22.15 <span class="help-inline">EUR</span> </td>
                    <td> <input type="text" name="discount[]" class="form-control input-xsmall input-inline"><span class="help-inline">EUR</span> </td>
                    <td> 699.00$ </td></tr>'];

        return $items;
    }

    /**
     * @param Request $request
     * @return array()
     */
    public function add_update_line_item(Request $request){
        // add new entry in order_items table
        if (!Auth::check()) {
            return redirect()->intended(route('admin/login'));
        }
        else{
            $user = Auth::user();
        }

        $vars = $request->only('productID', 'lineID', 'orderID', 'quantity', 'sell_price', 'discount_amount');

        if ($vars['orderID']==-1){
            // need to create a new order
            $order_number = substr( base64_encode(openssl_random_pseudo_bytes(32)),0 ,63 );
            $order_values = ['employee_id'=> $user->id,
                'buyer_id'      =>0,
                'order_number'  => $order_number,
                'discount_type' => 1,
                'discount_amount' => 0,
                'status' => 'pending'];
            $order = new Order($order_values);
            $order->save();
        }
        else{
            $order = Order::where('order_number','=',$vars['orderID'])->get()->first();
        }

        $product = Product::findOrFail($vars['productID']);
        $productListPrice = $product->get_list_price();
        $productCostPrice = $product->get_entry_price();
        $productVAT = $product->get_vat();
        $vatValue = ($productListPrice->list_price*$vars['quantity']*100)*($productVAT->value/100);
        $totalValue = ($productListPrice->list_price*$vars['quantity']*100)+$vatValue;

        $vatValue = round($vatValue)/100;
        $totalValue = round($totalValue)/100;

        if ($vars['lineID']==-1){
            $orderVars = ['order_id' => $order->id,
                'product_id' => $product->id,
                'quantity' => $vars['quantity'],
                'status' => 'ordered',
            ];
            $lineItem = new OrderItem($orderVars);
            $lineItem->save();
        }
        else{
            $lineItem = OrderItem::where('id','=',$vars->lineID)->where('order_id','=',$vars->orderID)->get()->list('id', 'order_id', 'product_id', 'quantity')->limit(1);
        }

        $returnVars = [
            'product_link'      => $product->url,
            'product_name'      => $product->name,
            'inventory_status'  => '15 in Stock',
            'currency'      => $productListPrice->currency->currency_code,
            'cost_price'    => $productCostPrice->entry_price,
            'sell_price'    => $productListPrice->list_price,
            'quantity'      => $vars['quantity'],
            'vat'           => $productVAT->value,
            'vat_value'     => $vatValue,
            'discount_value'    => '0',
            'total_amount'      => $totalValue,
            'item_line'     => $lineItem->id,
        ];

        if (isset($order_number)){
            $returnVars['orderID'] = $order_number;
        }

        return $returnVars;
    }
}
