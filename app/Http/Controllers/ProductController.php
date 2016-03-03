<?php

namespace App\Http\Controllers;

use App\Product;
use App\ProductCategories;
use App\ProductAvailability;
use App\VatRate;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;
use Validator;
use Auth;
use Hash;
use Webpatser\Countries\Countries;

class ProductController extends Controller
{
    public function __construct()
    {
        //$this->middleware('auth');
    }

    public function list_all(){
        if (!Auth::check()) {
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
            'title'     => 'Back-End Users',
            'subtitle'  => 'view all users',
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
        if (!Auth::check()) {
            return redirect()->intended(route('admin/login'));
        }

        $vars = $request->only('alternate_name', 'barcode', 'category_id', 'description', 'manufacturer', 'name', 'brand', 'vat_rate_id');
        $vars['url'] = str_slug($vars['manufacturer'] . ' ' . $vars['name']);

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
        if (!Auth::check()) {
            return redirect()->intended(route('admin/login'));
        }

        $product_details = Product::with('vat_rate')
                                  ->with('category')
                                  ->find($id);
        $product_availability = $this->get_availability($id);
        $vatRates = VatRate::orderBy('display_name')->get();
        $categories = ProductCategories::orderBy('name')->get();
        $currencies = Countries::distinct()->select('currency_code')->orderBy('currency')->groupBy('currency_code')->get(['id', 'currency', 'currency_code']);

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
        ]);
    }

    public function get_product_history(Request $request){
        if (!Auth::check()) {
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

    public function get_product_inventory(Request $request){
        if (!Auth::check()) {
            return redirect()->intended(route('admin/login'));
        }

        $iTotalRecords = 45;
        $iDisplayLength = intval($request['length']);
        $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength;
        $iDisplayStart = intval($request['start']);
        $sEcho = intval($request['draw']);

        $records = array();
        $records["data"] = array();

        $end = $iDisplayStart + $iDisplayLength;
        $end = $end > $iTotalRecords ? $iTotalRecords : $end;

        $status_list = array(
            array("info" => "Pending"),
            array("success" => "Approved"),
            array("danger" => "Rejected")
        );

        for($i = $iDisplayStart; $i < $end; $i++) {
            $status = $status_list[rand(0, 2)];
            $id = ($i + 1);
            $records["data"][] = array(
                $id,
                '12/09/2013',
                'Test Customer',
                'Very nice and useful product. I recommend to this everyone.',
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

    public function get_availability($id)
    {
        if (!Auth::check()) {
            return redirect()->intended(route('admin/login'));
        }

        $availability = ProductAvailability::where('product_id','=',$id)->get();

        return $availability;
    }

    public function update_product_availability(Request $request, $id){
        if (!Auth::check()) {
            return redirect()->intended(route('admin/login'));
        }

        $vars = $request->only('available_from', 'available_to');
        $vars['product_id'] = $id;
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
}
