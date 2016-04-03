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
use Carbon\Carbon;

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
        else{
            $user = Auth::user();
        }

        $shops = ShopLocations::all();
        $order_date = Carbon::now()->format('d-M-Y H:i');

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

        return view('admin/shops/add_new_order', [
            'shops'       => $shops,
            'breadcrumbs' => $breadcrumbs,
            'text_parts'  => $text_parts,
            'in_sidebar'  => $sidebar_link,
            'orderID'     => -1,
            'order_date'  => $order_date,
        ]);
    }

    /**
     * All list orders - draw table and search controllers
     */
    public function all_orders(){
        if (!Auth::check()) {
            return redirect()->intended(route('admin/login'));
        }
        else{
            $user = Auth::user();
        }

        $shops = ShopLocations::all();
        $usersList = User::all('id', 'first_name', 'middle_name', 'last_name');

        $breadcrumbs = [
            'Home'              => route('admin'),
            'Administration'    => route('admin'),
            'Products'          => route('admin/shops/products/all'),
            'Inventory'         => '',
        ];
        $text_parts  = [
            'title'     => 'Company Inventory',
            'subtitle'  => 'in / out',
            'table_head_text' => 'Inventory',
        ];
        $sidebar_link= 'admin-backend-shop-all_orders';

        return view('admin/orders/all_orders', [
            'breadcrumbs' => $breadcrumbs,
            'text_parts'  => $text_parts,
            'in_sidebar'  => $sidebar_link,
            'shops'       => $shops,
            'users'       => $usersList
        ]);
    }

    /**
     * Get all lists orders based on search criteria
     */
    public function get_all_orders(Request $request){
        if (!Auth::check()) {
            return redirect()->intended(route('admin/login'));
        }
        else{
            $user = Auth::user();
        }

        $where_clause = $request->only('t_inventory_amount', 't_inventory_date_from', 't_inventory_date_to', 't_inventory_product',
            't_inventory_list_price', 't_inventory_location', 't_inventory_employee', 'order[0][column]', 'order[0][dir]');
        $queryBuild = DB::table('inventories')
            ->select('inventories.*','shop_locations.*','products.name as product_name','users.first_name as first_name','users.middle_name as middle_name','users.last_name as last_name')
            ->join('users', 'users.id', '=', 'inventories.user_id')
            ->join('products', 'products.id', '=', 'inventories.product_id')
            ->join('shop_locations', 'shop_locations.id', '=', 'inventories.location_id');

        $amount_validator = Validator::make($where_clause, ["t_inventory_amount" => "required|integer"]);
        if ( $amount_validator->fails() ){
            // send error back
        }
        else{
            $queryBuild->where('inventories.quantity','>=',$where_clause["t_inventory_amount"]);
        }

        $from_validator = Validator::make($where_clause, ["t_inventory_date_from" => "required|date"]);
        if ( $from_validator->fails() ){
            // send error back
        }
        else{
            $from_date = Carbon::createFromFormat('d-m-Y', $where_clause["t_inventory_date_from"])->format('Y-m-d');
            $queryBuild->where('inventories.created_at', '>=', $from_date);
        }

        $from_validator = Validator::make($where_clause, ["t_inventory_date_to" => "required|date"]);
        if ( $from_validator->fails() ){
            // send error back
        }
        else{
            // since we have datetime in DB and we use date here, to get the current day as well we increment it to +1
            $to_date = Carbon::createFromFormat('d-m-Y', $where_clause["t_inventory_date_to"])->addDay()->format('Y-m-d');
            $queryBuild->where('inventories.created_at', '<', $to_date);
        }

        $from_validator = Validator::make($where_clause, ["t_inventory_product" => "required|min:1"]);
        if ( $from_validator->fails() ){
            // send error back
        }
        else {
            $queryBuild->where('products.name','like', '%'.$where_clause["t_inventory_product"].'%');
        }

        $from_validator = Validator::make($where_clause, ["t_inventory_list_price" => "required|numeric"]);
        if ( $from_validator->fails() ){
            // send error back
        }
        else {
            $queryBuild->where('inventories.entry_price','>=',$where_clause["t_inventory_list_price"]);
        }

        $from_validator = Validator::make($where_clause, ["t_inventory_location" => "required|numeric|exists:shop_locations,id"]);
        if ( $from_validator->fails() ){
            // send error back
        }
        else {
            $queryBuild->where('inventories.location_id','=',$where_clause["t_inventory_location"]);
        }

        $from_validator = Validator::make($where_clause, ["t_inventory_employee" => "required|numeric|exists:users,id"]);
        if ( $from_validator->fails() ){
            // send error back
        }
        else {
            //echo $where_clause["t_inventory_employee"]; exit;
            $queryBuild->where('inventories.user_id','=',$where_clause["t_inventory_employee"]);
        }

        // order by rules
        $orderColumn = $where_clause['order[0][column]'];
        $orderDirection = $where_clause['order[0][dir]'];
        switch($orderColumn){
            case 1 : // order by inventory_date
                $orderByFirst = 'inventories.created_at';
                break;
            case 2 : // order by amount
                $orderByFirst = 'inventories.quantity';
                break;
            case 3 : // order by list price
                $orderByFirst = 'inventories.entry_price';
                break;
            case 4 : // order by shop location
                $orderByFirst = 'inventories.location_id';
                break;
            case 5 : // order by shop location
                $orderByFirst = 'inventories.user_id';
                break;
            default : // order by Inventory ID
                $orderByFirst = 'inventories.id';
                break;
        }

        switch($orderDirection){
            case 'desc':
            case 'DESC':
                $orderBySecond = 'desc';
                break;
            default:
                $orderBySecond = 'asc';
                break;
        }

        $queryBuild->orderBy($orderByFirst, $orderBySecond);
        //$abd = $queryBuild->toSql();
        //dd($abd); exit;

        $results = $queryBuild->get();

        $vars = $request->only('length','start','draw','customActionType');

        $iTotalRecords = sizeof($results);
        $iDisplayLength = intval($vars['length']);
        $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength;
        $iDisplayStart = intval($vars['start']);
        $sEcho = intval($vars['draw']);

        $records = array();
        $records["data"] = array();

        $end = $iDisplayStart + $iDisplayLength;
        $end = $end > $iTotalRecords ? $iTotalRecords : $end;

        $status_list = array(
            array("info"    => "Pending"),
            array("success" => "Approved"),
            array("danger"  => "Rejected")
        );

        for($i = $iDisplayStart; $i < $end; $i++) {
            $status = $status_list[rand(0, 2)];
            //xdebug_var_dump($results[$i]);
            $id = ($i + 1);
            $records["data"][] = array(
                $id.'. <a href="'.route("admin/shops/products/view", $results[$i]->product_id).'">'.$results[$i]->product_name.'</a>',
                $results[$i]->created_at,
                $results[$i]->quantity,
                $results[$i]->entry_price,
                ' <a href="'.route("admin/shops/locations/view", $results[$i]->location_id).'" class="btn btn-sm btn-default btn-editable"><i class="fa fa-building"></i> '.$results[$i]->name.'</a> ',
                ' <a href="'.route("admin/back_users/view_user/", $results[$i]->user_id).'" class="btn btn-sm btn-default btn-editable"><i class="fa fa-user"></i> '.$results[$i]->first_name.' '.$results[$i]->middle_name.' '.$results[$i]->last_name.'</a> ',
                '',
            );
        }

        /*for($i = $iDisplayStart; $i < $end; $i++) {
            $status = $status_list[rand(0, 2)];
            $id = ($i + 1);
            $records["data"][] = array(
                $id,
                '12/09/2013',
                'Test Customer',
                'Very nice and useful product. I recommend to this everyone.',
                '<span class="label label-sm label-'.(key($status)).'">'.(current($status)).'</span>',
                'Test Users',
                '<a href="javascript:;" class="btn btn-sm btn-default btn-editable"><i class="fa fa-share"></i> View</a>',
            );
        }*/

        if (isset($vars["customActionType"]) && $vars["customActionType"] == "group_action") {
            $records["customActionStatus"] = "OK"; // pass custom message(useful for getting status of group actions)
            $records["customActionMessage"] = "Group action successfully has been completed. Well done!"; // pass custom message(useful for getting status of group actions)
        }

        $records["draw"] = $sEcho;
        $records["recordsTotal"] = $iTotalRecords;
        $records["recordsFiltered"] = $iTotalRecords;

        return $records;
    }

    public function get_order_details(Request $request, $id=-1){
        if (!Auth::check()) {
            return redirect()->intended(route('admin/login'));
        }
        else{
            $user = Auth::user();
        }

        $vars = $request->only('id');
        if ($id==-1){
            $id = $vars['id'];
        }

        $order = Order::where('order_number','=',$id)->get()->first();
        if ($order) {
            $order_date = Carbon::createFromFormat('Y-m-d', $order->created_at)->format('d-m-Y');

            $order_details = [
                'order_no' => @$order->id.' <span class="label label-info label-sm"> Email confirmation was sent </span> ',
                'order_date_time' => @$order_date,
                'order_status' => ' <span class="label label-success"> '.@$order->status.' </span> ',
                'order_total_price' => ' $175.25 ',
                'order_payment_info' => ' Credit Card ',
                'id' => @$order->order_number,
            ];
        }
        else {
            $order_details = [
                'order_no' => ' - ',
                'order_date_time' => Carbon::now()->format('d-M-Y H:i'),
                'order_status' => ' <span class="label label-success"> Not Started </span> ',
                'order_total_price' => ' - ',
                'order_payment_info' => ' - ',
                'id' => @$order->order_number,
            ];
        }

        return $order_details;
    }

    public function get_order_lines_items(Request $request, $id=-1){
        if (!Auth::check()) {
            return redirect()->intended(route('admin/login'));
        }
        else{
            $user = Auth::user();
        }

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
