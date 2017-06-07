<?php

namespace App\Http\Controllers;

use App\Inventory;
use App\Product;
use App\ProductCategories;
use App\ProductAvailability;
use App\ProductImage;
use App\ProductPrice;
use App\ShopLocations;
use App\VatRate;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;
use Validator;
use Auth;
use Hash;
use Storage;
use Webpatser\Countries\Countries;
use DB;

class ProductController extends Controller
{
    public function __construct()
    {
        //$this->middleware('auth');
    }

    public function list_all(){
        $user = Auth::user();
        if (!$user || !$user->is_back_user()) {
            return redirect()->intended(route('admin/login'));
        }

        $products = Product::orderBy('name')->get();
        $vat_rates = VatRate::orderBy('value')->get();
        $categories = ProductCategories::orderBy('name')->get();

        $formatted_vat = array();
        foreach($vat_rates as $vat){
            $formatted_vat[$vat['id']] = $vat['value'];
        }

        $formatted_cat = array();
        foreach($categories as $cat){
            $formatted_cat[$cat['id']] = $cat['name'];
        }

        $breadcrumbs = [
            'Home'              => route('admin'),
            'Administration'    => route('admin'),
            'Back End User'     => route('admin'),
            'All Backend Users' => '',
        ];
        $text_parts  = [
            'title'     => 'Products',
            'subtitle'  => 'view all system products',
            'table_head_text1' => 'Backend User List'
        ];
        $sidebar_link= 'admin-backend-shop-products-list';

        //xdebug_var_dump($formatted_vat);

        return view('admin/products/all_products_list', [
            'products'    => $products,
            'breadcrumbs' => $breadcrumbs,
            'text_parts'  => $text_parts,
            'in_sidebar'  => $sidebar_link,
            'vatRates'    => $vat_rates,
            'prodVatRates'   => $formatted_vat,
            'categories'     => $categories,
            'prodCategories' => $formatted_cat,
        ]);
    }

    public function create(Request $request){
        $user = Auth::user();
        if (!$user || !$user->is_back_user()) {
            return redirect()->intended(route('admin/login'));
        }

        $vars = $request->only('alternate_name', 'barcode', 'category_id', 'description', 'manufacturer', 'name', 'brand', 'vat_rate_id');
        $vars['url'] = str_slug($vars['manufacturer'] . ' ' . $vars['name']);
        $vars['status'] = 0;

        $validator = Validator::make($vars, Product::rules('POST'), Product::$message, Product::$attributeNames);

        if ($validator->fails()){
            return array(
                'success' => false,
                'errors' => $validator->getMessageBag()->toArray()
            );
        }

        try {
            Product::create($vars);
        } catch (Exception $e) {
            return Response::json(['error' => 'Product already exists.'], Response::HTTP_CONFLICT);
        }

        return $vars;
    }

    public function get_product($id){
        $user = Auth::user();
        if (!$user || !$user->is_back_user()) {
            return redirect()->intended(route('admin/login'));
        }

        $product_details = Product::with('vat_rate')
                                  ->with('category')
                                  ->find($id);
        $product_availability = $this->get_availability($id);
        $vatRates = VatRate::orderBy('display_name')->get();
        $categories = ProductCategories::orderBy('name')->get();

        $price = $this->get_price($id);
        $entry_price = Inventory::select('entry_price','created_at')->where('product_id','=',$id)->orderBy('created_at','desc')->get()->first();
        if ($entry_price) {
            $abc = $entry_price->created_at;
            $entry_price->added_on = $abc->format('d-m-Y');
        }

        $currencies = Countries::distinct()->
                                select(array('id', 'currency', 'currency_code'))->
                                where('currency_code','!=',"")->
                                orderBy('currency')->
                                groupBy('currency_code')->
                                get();
        $shops = ShopLocations::all();
        $usersList = User::all('id', 'first_name', 'middle_name', 'last_name');
        $stocks = $this->get_product_stock($id);
        //xdebug_var_dump($stocks);

        $product_images = ProductImage::where('product_id', '=', $id)->get();

        $breadcrumbs = [
            'Home'              => route('admin'),
            'Administration'    => route('admin'),
            'Products'          => route('admin/shops/products/all'),
            $product_details->name => '',
        ];
        $text_parts  = [
            'title'     => $product_details->name.' - Product Details',
            'subtitle'  => '',
            'table_head_text' => $product_details->name.' from '.$product_details->category->name,
        ];
        $sidebar_link= 'admin-backend-product-details-view';

        //xdebug_var_dump($product_availability);

        return view('admin/products/product_details', [
            'breadcrumbs' => $breadcrumbs,
            'text_parts'  => $text_parts,
            'in_sidebar'  => $sidebar_link,
            'product_details' => $product_details,
            'product_availability' => $product_availability,
            'vat_rates' => $vatRates,
            'categories' => $categories,
            'currencies' => $currencies,
            'price' => $price,
            'entry_price' => $entry_price,
            'shops' => $shops,
            'users' => $usersList,
            'stocks' => $stocks,
            'product_images' => $product_images,
        ]);
    }

    public function update_product(Request $request, $id){
        $user = Auth::user();
        if (!$user || !$user->is_back_user()) {
            return redirect()->intended(route('admin/login'));
        }

        /** @var $prod_vars - updated fields for the product that is edited */
        $prod_vars = $request->only('name', 'alternate_name', 'category_id', 'brand', 'manufacturer', 'description', 'url', 'barcode', 'status', 'vat_rate_id');
        $prod_vars['id'] = $id;
        $prod_validator = Validator::make($prod_vars, Product::rules('PUT', $id), Product::$message, Product::$attributeNames);
        if ($prod_validator->fails()){
            return array(
                'success' => false,
                'errors' => $prod_validator->getMessageBag()->toArray()
            );
        }
        else {
            $product_det = Product::find($id);
            $product_det->fill($prod_vars);
            $product_det->save();
        }

        $availability_vars = $request->only('available_from','available_to');
        $availability_vars['product_id'] = $id;

        $old_availability = $this->get_availability($id);
        if ($old_availability['available_from']!=$availability_vars['available_from'] || $old_availability['available_to']!=$availability_vars['available_to']){
            // availability changed, so we update it
            $availability_vars['available_from'] =  Carbon::createFromFormat('d-m-Y', $availability_vars['available_from'])->format('Y-m-d');
            $availability_vars['available_to'] =  Carbon::createFromFormat('d-m-Y', $availability_vars['available_to'])->format('Y-m-d');

            $availability_validator = Validator::make($availability_vars, ProductAvailability::rules('POST'), ProductAvailability::$message, ProductAvailability::$attributeNames);
            if ($availability_validator->fails()){
                //echo 'Naspa'; exit;
            }
            else{
                ProductAvailability::create($availability_vars);
            }
        }

        $price_vars = $request->only('list_price', 'country_id');
        $this->update_price($id, $price_vars['list_price'], $price_vars['country_id']);
        /*$currentTime = Carbon::now();
        $last_price = ProductPrice::where('product_id', $id)->orderBy('updated_at', 'desc')->get()->first();
        if ($last_price){
            // we have a defined price in database, let's see if we need to update it
            if ($last_price->list_price != $price_vars['list_price'] || $last_price->country_id != $price_vars['country_id']){
                // we update the old price end date
                $last_price->end_date = $currentTime->toDateTimeString();
                $last_price->save();
                $old_price_updated = true;
            }
        }

        if ( ($last_price && isset($old_price_updated)) || !$last_price){
            // we need to add the price to the database - old price was different or there is no old price
            $price_vars['product_id'] = $id;
            $price_vars['start_date'] = $currentTime->toDateTimeString();
//xdebug_var_dump($price_vars); //exit;
            $price_validator = Validator::make($price_vars, ProductPrice::rules('POST'), ProductPrice::$message, ProductPrice::$attributeNames);

            if ($price_validator->fails()){
                return $price_validator->errors()->all(); exit;
            }
            else{
                ProductPrice::create($price_vars);
            }
        }*/

        return "bine";
    }

    public function get_product_history(Request $request){
        $user = Auth::user();
        if (!$user || !$user->is_back_user()) {
            return redirect()->intended(route('admin/login'));
        }

        $iTotalRecords = 120;
        $iDisplayLength = intval($request['length']);
        $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength;
        $iDisplayStart = intval($request['start']);
        $sEcho = intval($request['draw']);

        $records = array();
        $records["data"] = array();

        $end = $iDisplayStart + $iDisplayLength;
        $end = $end > $iTotalRecords ? $iTotalRecords : $end;

        $status_list = array(
            array("default" => "Pending"),
            array("default" => "Notified"),
            array("danger" => "Failed")
        );

        for($i = $iDisplayStart; $i < $end; $i++) {
            $status = $status_list[rand(0, 2)];
            $records["data"][] = array(
                '12/09/2013 09:20:45',
                'Product has been purchased. Pending for delivery',
                '<span class="label label-sm label-'.(key($status)).'">'.(current($status)).'</span>',
                '<a href="javascript:;" class="btn btn-sm btn-default btn-editable"><i class="fa fa-share"></i> View</a>',
            );
        }

        if (isset($request["customActionType"]) && $request["customActionType"] == "group_action") {
            $records["customActionStatus"] = "OK"; // pass custom message(useful for getting status of group actions)
            $records["customActionMessage"] = "Group action successfully has been completed. Well done!"; // pass custom message(useful for getting status of group actions)
        }

        $records["draw"] = $sEcho;
        $records["recordsTotal"] = $iTotalRecords;
        $records["recordsFiltered"] = $iTotalRecords;

        return $records;
    }

    public function get_product_inventory(Request $request, $id){
        $user = Auth::user();
        if (!$user || !$user->is_back_user()) {
            return redirect()->intended(route('admin/login'));
        }

        $where_clause = $request->only('t_inventory_amount', 't_inventory_date_from', 't_inventory_date_to', 't_inventory_id_no',
                                       't_inventory_list_price', 't_inventory_location', 't_inventory_employee', 'order', 'order');
        //xdebug_var_dump($where_clause);
        $where_clause['product_id'] = $id;
        $queryBuild = DB::table('inventories')
                        ->select('inventories.created_at','inventories.quantity','inventories.entry_price','shop_locations.id as location_id','shop_locations.name as name',
                                 'users.id as user_id','users.first_name','users.middle_name','users.last_name')
                        ->join('users', 'users.id', '=', 'inventories.user_id')
                        ->join('shop_locations', 'shop_locations.id', '=', 'inventories.location_id')
                        ->where('inventories.product_id','=',$id);

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

        $from_validator = Validator::make($where_clause, ["t_inventory_id_no" => "required|integer"]);
        if ( $from_validator->fails() ){
            // send error back
        }
        else {
            $queryBuild->where('inventories.id','=',$where_clause["t_inventory_id_no"]);
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
        $orderColumn = $where_clause['order'][0]['column'];
        $orderDirection = $where_clause['order'][0]['dir'];
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
        $abd = $queryBuild->toSql();
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
                $id,
                $results[$i]->created_at,
                $results[$i]->quantity,
                $results[$i]->entry_price,
                '<a href="'.route("admin/shops/locations/view", $results[$i]->location_id).'" class="btn btn-sm btn-default btn-editable"><i class="fa fa-building"></i> '.$results[$i]->name.'</a>',
                '<a href="'.route("admin/back_users/view_user/", $results[$i]->user_id).'" class="btn btn-sm btn-default btn-editable"><i class="fa fa-user"></i> '.$results[$i]->first_name.' '.$results[$i]->middle_name.' '.$results[$i]->last_name.'</a>',
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

    public function get_all_products_inventory(Request $request){
        $user = Auth::user();
        if (!$user || !$user->is_back_user()) {
            return redirect()->intended(route('admin/login'));
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

    public function get_availability($id)
    {
        $user = Auth::user();
        if (!$user || !$user->is_back_user()) {
            return redirect()->intended(route('admin/login'));
        }

        $availability = ProductAvailability::where('product_id','=',$id)->orderBy('updated_at','desc')->get()->first();
        //xdebug_var_dump($availability);
        if ($availability) {
            $availability['available_from'] = Carbon::createFromFormat('Y-m-d', $availability['available_from'])->format('d-m-Y');
            $availability['available_to']   = Carbon::createFromFormat('Y-m-d', $availability['available_to'])->format('d-m-Y');
        }

        return $availability;
    }

    public function update_product_availability(Request $request, $id){
        $user = Auth::user();
        if (!$user || !$user->is_back_user()) {
            return redirect()->intended(route('admin/login'));
        }

        $vars = $request->only('available_from', 'available_to');
        $vars['product_id'] = $id;
        $vars['available_from'] =  Carbon::createFromFormat('d-m-Y', $vars['available_from'])->format('Y-m-d');
        $vars['available_to'] =  Carbon::createFromFormat('d-m-Y', $vars['available_to'])->format('Y-m-d');

        $validator = Validator::make($vars, ProductAvailability::rules('POST'), ProductAvailability::$message, ProductAvailability::$attributeNames);

        if ($validator->fails()){
            return array(
                'success' => false,
                'errors' => $validator->getMessageBag()->toArray()
            );
        }

        try {
            ProductAvailability::create($vars);
        } catch (Exception $e) {
            return Response::json(['error' => 'Something went wrong.'], Response::HTTP_CONFLICT);
        }

        return 'bine';
    }

    public function get_price($id){
        $user = Auth::user();
        if (!$user || !$user->is_back_user()) {
            return redirect()->intended(route('admin/login'));
        }

        $price = ProductPrice::with('currency')->where('product_id','=',$id)->orderBy('updated_at','desc')->get()->first();
        //xdebug_var_dump($price);

        return $price;
    }

    public function get_entry_price($id){
        $entry_price = Inventory::select('entry_price','created_at')->where('product_id','=',$id)->orderBy('created_at','desc')->get()->first();
        if ($entry_price) {
            $abc = $entry_price->created_at;
            $entry_price->added_on = $abc->format('d-m-Y');
        }

        return $entry_price;
    }

    public function update_price($product_id, $new_price, $new_currency){
        $user = Auth::user();
        if (!$user || !$user->is_back_user()) {
            return redirect()->intended(route('admin/login'));
        }

        $price_vars = array('list_price'=>$new_price, 'country_id'=>$new_currency, 'product_id'=>$product_id);
        $currentTime = Carbon::now();
        $last_price = ProductPrice::where('product_id', $product_id)->orderBy('updated_at', 'desc')->get()->first();
        if ($last_price){
            // we have a defined price in database, let's see if we need to update it
            if ($last_price->list_price != $price_vars['list_price'] || $last_price->country_id != $price_vars['country_id']){
                // we update the old price end date
                $last_price->end_date = $currentTime->toDateString();
                $last_price->save();
                $old_price_updated = true;
            }
        }

        if ( ($last_price && isset($old_price_updated)) || !$last_price){
            // we need to add the price to the database - old price was different or there is no old price
            $price_vars['start_date'] = $currentTime->toDateTimeString();
            $price_validator = Validator::make($price_vars, ProductPrice::rules('POST'), ProductPrice::$message, ProductPrice::$attributeNames);

            if ($price_validator->fails()){
                return $price_validator->errors()->all();
            }
            else{
                ProductPrice::create($price_vars);
            }
        }

        return true;
    }

    public function all_inventory(){
        $user = Auth::user();
        if (!$user || !$user->is_back_user()) {
            return redirect()->intended(route('admin/login'));
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
            'title'     => 'Products Inventory Log',
            'subtitle'  => 'in / out',
            'table_head_text' => 'Inventory',
        ];
        $sidebar_link= 'admin-backend-all-products-inventory';

        return view('admin/products/all_inventory', [
            'breadcrumbs' => $breadcrumbs,
            'text_parts'  => $text_parts,
            'in_sidebar'  => $sidebar_link,
            'shops'       => $shops,
            'users'       => $usersList
        ]);
    }

    public function add_to_inventory(Request $request){
        $user = Auth::user();
        if (!$user || !$user->is_back_user()) {
            return redirect()->intended(route('admin/login'));
        }

        $vars = $request->only('entry_price', 'quantity', 'location_id', 'product_id');
        //$vars['product_id'] = $id;
        $vars['user_id'] = $user->id;

        // check if product exists
        $product = Product::find($vars['product_id']);
        if (!$product){
            return "Product error";
        }

        // check old price and compare it with the new
        $price = $this->get_price($vars['product_id']);
        if (!$price){
            return "Price error!";
        }

        // check if location exists
        $location = ShopLocations::find($vars['location_id']);
        if (!$location){
            return 'Shop Location Error';
        }

        $validator = Validator::make($vars, Inventory::rules('POST'), Inventory::$message, Inventory::$attributeNames);

        if ($validator->fails()){
            return array(
                'success' => false,
                'errors' => $validator->getMessageBag()->toArray()
            );
        }

        $newInventory = new Inventory();
        $newInventory->fill($vars);
        $newInventory->save();

        if ($newInventory->wasRecentlyCreated){
            /** @var  $sell_price : when true, the entry_price is the sell price, else entry_price is the price the company buys the product */
            $sell_price = false;

            // check if the inventory price is the same as the list price
            if ($vars['entry_price']!=$price['list_price'] && $sell_price){
                // we update the list price
                $this->update_price($vars['product_id'], $vars['entry_price'], $price['country_id']);
            }
        }

        return array(
            'success' => true,
            'message' => 'Inventory successfully added'
        );
    }

    public function transfer_from_inventory(Request $request){
        $user = Auth::user();
        if (!$user || !$user->is_back_user()) {
            return redirect()->intended(route('admin/login'));
        }

        $vars = $request->only('quantity', 'old_location_id', 'new_location_id', 'product_id');
        $vars['user_id'] = $user->id;

        $entry_price = $this->get_entry_price($vars['product_id']);
        $vars["entry_price"] = isset($entry_price->entry_price)?$entry_price->entry_price:0;

        // check if stock is valid
        $stock = $this->get_product_stock($vars['product_id'], $vars['old_location_id']);
        //xdebug_var_dump($stock, $vars['old_location_id']); exit;
        if (!$stock){
            return array(
                'success' => false,
                'errors'  => 'Stock error - nothing in stock ',
            );
        }
        elseif ($vars['quantity']>$stock['quantity']){
            return array(
                'success' => false,
                'errors'  => 'Stock too low - only '.$stock['quantity'].' in selected location',
            );
        }
        //xdebug_var_dump($stock); exit;

        $validator = Validator::make($vars, Inventory::rules('PATCH'), Inventory::$message, Inventory::$attributeNames);

        if ($validator->fails()){
            return array(
                'success' => false,
                'errors' => $validator->getMessageBag()->toArray()
            );
        }

        $vars_from = array('product_id'=>$vars['product_id'], 'location_id'=>$vars['old_location_id'], 'quantity'=>(-$vars['quantity']), 'entry_price'=>$vars['entry_price'], 'user_id'=>$vars['user_id']);
        $newInventoryFrom = new Inventory();
        $newInventoryFrom->fill($vars_from);
        $newInventoryFrom->save();

        $vars_to = array('product_id'=>$vars['product_id'], 'location_id'=>$vars['new_location_id'], 'quantity'=>$vars['quantity'], 'entry_price'=>$vars['entry_price'], 'user_id'=>$vars['user_id']);
        $newInventoryTo = new Inventory();
        $newInventoryTo->fill($vars_to);
        $newInventoryTo->save();
        /*if ($newInventoryTo->wasRecentlyCreated){
            // @var  $sell_price : when true, the entry_price is the sell price, else entry_price is the price the company buys the product
            $sell_price = false;

            // check if the inventory price is the same as the list price
            if ($vars['entry_price']!=$price['list_price'] && $sell_price){
                // we update the list price
                $this->update_price($id, $vars['entry_price'], $price['country_id']);
            }
        }*/

        return array(
            'success' => true,
            'message' => 'Inventory successfully transfered'
        );
    }

    public function get_product_stock($productID, $shopID=0){
        $user = Auth::user();
        if (!$user || !$user->is_back_user()) {
            return redirect()->intended(route('admin/login'));
        }

        // check if product exists
        $product = Product::find($productID);
        if (!$product){
            return false;
        }

        $query = DB::table('inventories')
            ->select(DB::raw('sum(quantity) as quantity'), 'shop_locations.name as location_name', 'shop_locations.id as location_id')
            ->join('shop_locations','inventories.location_id','=','shop_locations.id')
            ->where('product_id',"=",$productID)
            ->groupBy('location_id');

        // if we get one locationID -> check if location exists
        if ($shopID!=0) {
            $shop = ShopLocations::find($shopID);
            if (!$shop) {
                return false;
            }
            else {
                $query->where('location_id', $shopID);
            }
        }
        $results = $query->get();

        if (isset($results)){
            $arrayResult = array();
            if ($shopID==0) {
                foreach ($results as $result) {
                    $arrayResult[$result->location_id] = array('quantity' => (int)$result->quantity, 'location_name' => $result->location_name, 'location_id' => $result->location_id);
                }
            }
            else{
                foreach ($results as $result) {
                    $arrayResult = array('quantity' => (int)$result->quantity, 'location_name' => $result->location_name, 'location_id' => $result->location_id);
                }
            }

            return $arrayResult;
        }
        else{
            return 0;
        }
    }

    public function ajax_get(Request $request){
        $user = Auth::user();
        if (!$user || !$user->is_back_user()) {
            return redirect()->intended(route('admin/login'));
        }

        $vars = $request->only('q');
        $formatSearch = str_replace(' ','%',$vars['q']);
        $items_array = array();
        $items = array();

        $query = DB::table('products')
            ->select('products.name','products.alternate_name','products.manufacturer','products.id','product_categories.name as category_name')
            ->join('product_categories','product_categories.id','=','products.category_id')
            ->where(    'products.name','like','%'.$formatSearch.'%')
            ->orWhere(  'products.alternate_name','like','%'.$formatSearch.'%')
            ->orWhere(  'products.brand','like','%'.$formatSearch.'%')
            ->orWhere(  'products.manufacturer','like','%'.$formatSearch.'%')
            ->orWhere(  'product_categories.name','like','%'.$formatSearch.'%');

        $results = $query->get();
        if ($results){
            foreach($results as $result){
                $product_price = $this->get_price($result->id);
                $product_entry_price = $this->get_entry_price($result->id);

                $items[] = array('id'=>$result->id,
                    'full_name' => $result->name,
                    'text' => $result->alternate_name,
                    'product_name'=>$result->name,
                    'manufacturer'=>$result->manufacturer,
                    'category'=>$result->category_name,
                    'list_price'=>$product_price->list_price,
                    'entry_price'=>@$product_entry_price->entry_price,
                    'currency'=>$product_price->currency->currency_code,
                    'product_image_url' => asset('assets/pages/img/avatars/team'.rand(1,10).'.jpg')
                );
            }
        }

        $items_array['items'] = $items;

        return $items_array;
    }

    public function add_product_image(Request $request, $id){
        $user = Auth::user();
        if (!$user || !$user->is_back_user()) {
            return redirect()->intended(route('admin/login'));
        }

        $product = Product::findOrFail($id);
        $documentLabel = $request->file('file')->getClientOriginalName();
        $documentLocation = 'products/'.$product->id.'/img/'.str_slug($documentLabel);
        $exists = Storage::disk('uploads')->exists($documentLocation);
        if ($exists){
            return "Error";
        }

        $productImage = [
            'product_id'   => $product->id,
            'label' => $documentLabel,
            'file_location' => $documentLocation,
        ];

        $document = new ProductImage();
        $document->fill($productImage);
        $document->save();

        Storage::disk('uploads')->put(
            $documentLocation,
            file_get_contents($request->file('file')->getRealPath())
        );

        return "Bine";
        //return redirect('admin/back_users/view_user/'.$id);
    }

}
