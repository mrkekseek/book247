<?php

namespace App\Http\Controllers;

use App\Order;
use App\OrderInvoice;
use App\OrderInvoiceItem;
use App\OrderItem;
use App\Product;
use Illuminate\Http\Request;
use App\ShopLocations;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\User;
use Auth;
use DB;
use Validator;
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

    public function view_order($id){
        if (!Auth::check()) {
            return redirect()->intended(route('admin/login'));
        }
        else{
            $user = Auth::user();
        }

        $order = Order::where('id','=',$id)->get()->first();

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
            'order'       => $order,
        ]);
    }

    public function save_order(Request $request, $id = -1){
        //xdebug_var_dump($request); exit;

        $orderDetails = $request->only('orderLineInfo', 'order_number_id');
        $order = Order::where('order_number','=',$orderDetails['order_number_id'])->get()->first();
        if (!$order){
            return 'false';
        }

        // find or generate the invoice based on the order lines
        $orderInvoice = OrderInvoice::firstOrCreate(['order_id'=>$order->id]);
        if ($orderInvoice->wasRecentlyCreated){
            // an invoice is already present so we update necessary fields
        }
        else{
            // an invoice will need to be created
            $orderInvoiceNumber = substr( base64_encode(openssl_random_pseudo_bytes(32)),0 ,63 );
            $testInvoice = $orderInvoice::where('invoice_number', '=', $orderInvoiceNumber)->get()->first();
            while ($testInvoice){
                $orderInvoiceNumber = substr( base64_encode(openssl_random_pseudo_bytes(32)),0 ,63 );
                $testInvoice = $orderInvoice::where('invoice_number', '=', $orderInvoiceNumber)->get()->first();
            }

            $orderInvoice->order_id = $order->id;
            $orderInvoice->invoice_number = $orderInvoiceNumber;
            $orderInvoice->status = 'pending';
        }
        $orderInvoice->save();

        foreach ($orderDetails['orderLineInfo'] as $line){
            $lineID = $line;
            $orderItem = OrderItem::where('order_id','=',$order->id)->where('id','=',$lineID)->get()->first();
            if (!$orderItem){
                continue;
            }

            $product = Product::where('id','=',$orderItem->product_id)->get()->first();
            if (!$product){
                continue;
            }
            $product_entry_price = $product->get_entry_price();
            $product_list_price = $product->get_list_price();
            $product_vat = $product->get_vat();

            $lineDiscount   = $request['orderLineDiscount_'.$lineID];
            $discount       = $request['discount_'.$lineID];
            $lineSellPrice  = $request['orderLineSellPrice_'.$lineID];
            $sellPrice      = $request['sell_price_'.$lineID];
            $lineQuantity   = $request['orderLineQuantity_'.$lineID];
            $quantity       = $request['quantity_'.$lineID];
            $lineVAT        = $request['orderLineVAT_'.$lineID];

            if ($lineDiscount!=$discount){
                // something is wrong
            }

            $itemOrderDetails = json_encode(['discount'=>$discount, 'sell_price'=>$sellPrice]); //xdebug_var_dump($itemOrderDetails); exit;
            $orderItemFillable = ['quantity'=>$quantity, 'status'=>'ordered', 'other_details'=>$itemOrderDetails];
            $orderItem->update($orderItemFillable);

            if ($lineSellPrice!=$sellPrice || $lineQuantity!=$quantity){
                // something is wrong
            }

            $total_cost = ($sellPrice*100*$quantity)*((100+$lineVAT)/100);
            $total_cost = $total_cost - ($total_cost*($discount/100));
            //var vatAmount = (price*quantity)*(vat/100);
            //var totalAmount = (price*quantity) + vatAmount;
            //totalAmount = totalAmount - (totalAmount*(discount/100));

            $total_cost = round($total_cost);
            //totalAmount = Math.round(totalAmount);

            $invoiceItemFillable = ['invoice_id' => $orderInvoice->id,
                'order_item_id'         => $lineID,
                'product_id'            => $product->id,
                'product_name'          => $product->name,
                'product_quantity'      => $quantity,
                'product_cost'          => $product_entry_price->entry_price,
                'product_price'         => $product_list_price->list_price,
                'product_manual_price'  => $sellPrice,
                'product_discount'      => $discount,
                'product_vat'           => $product_vat->value,
                'product_total_cost'    => $total_cost/100];

            $invoiceItem = OrderInvoiceItem::firstOrCreate(['invoice_id'=>$orderInvoice->id, 'order_item_id'=>$lineID]);
            $invoiceItem->update($invoiceItemFillable);
        }

        return redirect()->intended(route('admin/shops/view_order_details', ['id' => $order->id]));
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
            $order_date = Carbon::createFromFormat('Y-m-d H:i:s', $order->created_at)->format('d-M-Y');

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

        $vars = $request->only('orderID');
        $order = Order::where('id','=',$id)->where('order_number','=',$vars['orderID'])->get()->first();
        if (!$order){
            // no order found
            return 'error';
        }

        $orderItems = OrderItem::where('order_id','=',$order->id)->where('status','=','ordered')->get();
        foreach($orderItems as $orderItem){
            $orderLine = OrderInvoiceItem::where('order_item_id','=',$orderItem->id)->get()->first();

            if (!$orderLine){
                continue;
            }
            $pid = $orderLine->product_id;
            $product = Product::where('id','=',$pid)->get()->first();
            if (!$product){
                continue;
            }
            $product_entry_price = $product->get_entry_price();
            $product_list_price = $product->get_list_price();
            $product_vat = $product->get_vat();

            $item = '<tr data-info="'.$orderItem->id.'">
                <td>
                    <a href="pepsi-pitesti-mirinda"> '.$orderLine->product_name.' </a> </td>
                <td>
                    <span class="label label-sm label-success">15 in Stock</span> </td>
                <td>
                    '.$orderLine->product_cost.'<span class="help-inline">BMD</span> </td>
                <td>
                    <input type="text" name="sell_price_'.$orderItem->id.'" value="'.$orderLine->product_manual_price.'" class="form-control input-inline input-xsmall lineSellPrice"><span class="help-inline">BMD</span> </td>
                <td>
                    <input type="text" value="'.$orderLine->product_quantity.'" name="quantity_'.$orderItem->id.'" class="form-control input-xsmall lineQuantity"> </td>
                <td>
                    '.$product_vat->value.'% </td>
                <td>
                    <span class="vat_amount">'.(round($orderLine->product_manual_price*$orderLine->product_quantity*$product_vat->value)/100).'</span> <span class="help-inline">BMD</span> </td>
                <td>
                    <input type="text" value="'.$orderLine->product_discount.'" name="discount_'.$orderItem->id.'" class="form-control input-xsmall input-inline lineDiscount"><span class="help-inline">%</span> </td>
                <td>
                    <span class="total_amount">'.$orderLine->product_total_cost.'</span><span class="help-inline">BMD</span> </td>
                <input type="hidden" value="'.$orderLine->product_manual_price.'" name="orderLineSellPrice_'.$orderItem->id.'">
                <input type="hidden" value="'.$orderLine->product_quantity.'" name="orderLineQuantity_'.$orderItem->id.'">
                <input type="hidden" value="'.$orderLine->product_vat.'" name="orderLineVAT_'.$orderItem->id.'">
                <input type="hidden" value="'.$orderLine->product_discount.'" name="orderLineDiscount_'.$orderItem->id.'">
                <input type="hidden" value="'.$orderItem->id.'" name="orderLineInfo[]">
            </tr>';
            $items[] = $item;
        }

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

        $vars = $request->only('productID', 'lineID', 'orderID', 'quantity', 'sell_price', 'discount_amount', 'buyerID');

        if ($vars['orderID']==-1){
            // need to create a new order
            $order_number = substr( base64_encode(openssl_random_pseudo_bytes(32)),0 ,63 );
            $order_values = ['employee_id'=> $user->id,
                'buyer_id'      =>$vars['buyerID'],
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
