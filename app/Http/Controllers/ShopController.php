<?php

namespace App\Http\Controllers;

use App\Address;
use App\CashTerminal;
use App\Product;
use App\ProductCategories;
use App\ShopLocations;
use App\ShopOpeningHour;
use App\ShopResource;
use App\ShopResourceCategory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use Validator;
use DB;
use Webpatser\Countries\Countries;

class ShopController extends Controller
{
    //
    public function list_all(){
        $user = Auth::user();
        if (!$user || !$user->is_back_user()) {
            return redirect()->intended(route('admin/login'));
        }

        $shop_locations = ShopLocations::get();
        foreach($shop_locations as $shop_loc){
            $var_addr = Address::find($shop_loc->address_id);
            if (isset($var_addr)){
                $all_address_fields = array(
                    $var_addr['attributes']['address1'],
                    $var_addr['attributes']['address2'],
                    $var_addr['attributes']['city'],
                    $var_addr['attributes']['postal_code'],
                    '<br />'.$var_addr['attributes']['region'],
                );

                $var_country = Countries::find($var_addr['attributes']['country_id']);
                $all_address_fields[] = $var_country->name;

                $shop_loc->full_address = implode(', ',$all_address_fields);
            }
        }

        $countries = Countries::orderBy('name')->get();

        $breadcrumbs = [
            'Home'              => route('admin'),
            'Administration'    => route('admin'),
            'Shop'              => route('admin'),
            'Shop Locations'    => '',
        ];
        $text_parts  = [
            'title'     => 'Shop Locations',
            'subtitle'  => 'add/edit/view locations',
            'table_head_text1' => 'Backend Shop Locations List'
        ];
        $sidebar_link= 'admin-backend-shop-locations-list';

        return view('admin/shops/all_locations', [
            'breadcrumbs'   => $breadcrumbs,
            'text_parts'    => $text_parts,
            'in_sidebar'    => $sidebar_link,
            'shopLocations' => $shop_locations,
            'countries'     => $countries,
        ]);
    }

    public function add_shop_location(Request $request){
        if (!Auth::check()) {
            return [
                'success'   => false,
                'title'     => 'Login Error',
                'errors'    => 'You need to be logged in to access this function'
            ];
        }
        else{
            $user = Auth::user();
        }

        $shop_vars = $request->only('name', 'bank_acc_no', 'phone', 'fax', 'email', 'registered_no');
        $address_vars = $request->only('address1', 'address2', 'city', 'country_id', 'postal_code', 'region');

        /** Start - Add shop location to database */
        $shopValidator = Validator::make($shop_vars, ShopLocations::rules('POST'), ShopLocations::$validationMessages, ShopLocations::$attributeNames);

        if ($shopValidator->fails()){
            //return $validator->errors()->all();
            return array(
                'success'   => false,
                'title'     => 'Error Shop Details',
                'errors'    => $shopValidator->getMessageBag()->toArray()
            );
        }
        else{
            $shopLocation = new ShopLocations();
            $shopLocation->fill($shop_vars);
            $shopLocation->save();
        }
        /** Stop - Add shop location to database */

        /** Start - Add address to database */
        $addressValidator = Validator::make($address_vars, Address::rules('POST'), Address::$validationMessages, Address::$attributeNames);

        if ($addressValidator->fails()){
            return array(
                'success'   => false,
                'title'     => 'Error Shop Address',
                'errors'    => $addressValidator->getMessageBag()->toArray()
            );
        }
        else{
            $shopAddress = new Address();
            $shopAddress->fill($address_vars);
            $shopAddress->save();

            $shopLocation->address_id = $shopAddress->id;
            $shopLocation->save();

            return [
                'success'   => true,
                'title'     => 'New Shop Added',
                'message'   => 'A new shop location was added. Page will reload...'
            ];
        }
        /** Stop - Add address to database */
    }

    public function get_shop_location($id){
        $user = Auth::user();
        if (!$user || !$user->is_back_user()) {
            return redirect()->intended(route('admin/login'));
        }

        $shopDetails = ShopLocations::with('opening_hours')->find($id);
        $shopAddress = Address::find($shopDetails->address_id);
        $shopOpeningHours = [];
        foreach ($shopDetails->opening_hours as $open_hour){
            if ($open_hour->entry_type=='day'){
                $shopOpeningHours[$open_hour->day_of_week] = [
                    'open_at' => $open_hour->open_at,
                    'close_at' => $open_hour->close_at,
                    'break_from' => $open_hour->break_from,
                    'break_to' => $open_hour->break_to,
                ];
            }
        }
        //xdebug_var_dump($shopOpeningHours);

        $addressCountry = Countries::find($shopAddress->country_id);
        $shopAddress->countryName = $addressCountry->name;

        $weekDays = [
            '1' => 'Sunday',
            '2' => 'Monday',
            '3' => 'Tuesday',
            '4' => 'Wednesday',
            '5' => 'Thursday',
            '6' => 'Friday',
            '7' => 'Saturday',
        ];

        $resourceCategories = ShopResourceCategory::orderBy('name','asc')->get();
        $shopResources = ShopResource::with('category')->where('location_id','=',$shopDetails->id)->orderBy('category_id')->get();

        $breadcrumbs = [
            'Home'              => route('admin'),
            'Administration'    => route('admin'),
            'Shop'              => route('admin'),
            'Shop Locations'    => '',
        ];
        $text_parts  = [
            'title'     => 'Shop Locations',
            'subtitle'  => 'view location details',
            'table_head_text1' => 'Backend Shop Location Details'
        ];
        $sidebar_link= 'admin-backend-shop-locations-details-view';

        return view('admin/shops/get_location', [
            'breadcrumbs'   => $breadcrumbs,
            'text_parts'    => $text_parts,
            'in_sidebar'    => $sidebar_link,
            'shopDetails'   => $shopDetails,
            'shopAddress'   => $shopAddress,
            'weekDays'      => $weekDays,
            'opening_hours' => $shopOpeningHours,
            'resourceCategory' => $resourceCategories,
            'resourceList'  => $shopResources,
        ]);
    }

    public function view_shop_location(){
        $user = Auth::user();
        if (!$user || !$user->is_back_user()) {
            return redirect()->intended(route('admin/login'));
        }


    }

    public function update_opening_hours(Request $request, $id){
        if (!Auth::check()) {
            return [
                'success'   => false,
                'title'     => 'Login Error',
                'errors'    => 'You need to be logged in to access this function'
            ];
        }
        else{
            $user = Auth::user();
        }

        $vars = $request->only('opening_hours','shopID');
        $opening_hours = $vars['opening_hours'];
        $open = [];
        $close = [];
        $break_from = [];
        $break_to = [];

        for ($i=1; $i<8; $i++) {
            foreach ($opening_hours as $val) {
                if (     $val['name'] == 'open_'.$i ){
                    $open[$i] = $val['value'];
                }
                if ( $val['name'] == 'close_'.$i ){
                    $close[$i] = $val['value'];
                }
                if ( $val['name'] == 'break_from_'.$i ){
                    $break_from[$i] = $val['value'];
                }
                if ( $val['name'] == 'break_to_'.$i ){
                    $break_to[$i] = $val['value'];
                }
            }
        }

        $shopLocation = ShopLocations::findOrFail($id);
        for ($i=1; $i<=7; $i++){
            $openingHour = ShopOpeningHour::firstOrNew(['entry_type'=>'day', 'day_of_week'=>$i, 'location_id'=>$shopLocation->id]);
            $openingHour->fill(['entry_type'=>'day', 'location_id'=>$id, 'day_of_week'=>$i,
                                'open_at'=>$open[$i], 'close_at'=>$close[$i], 'break_from'=> $break_from[$i], 'break_to'=> $break_to[$i]]);
            $openingHour->save();
        }

        return [
            'success'   => true,
            'title'     => 'Hours Updated',
            'message'   => 'Opening Hours Successfully Updated'
        ];
    }

    public function store_details_update(Request $request, $id){
        if (!Auth::check()) {
            return [
                'success'   => false,
                'title'     => 'Login Error',
                'errors'    => 'You need to be logged in to access this function'
            ];
        }
        else{
            $user = Auth::user();
        }

        $shop_vars = $request->only('name', 'bank_acc_no', 'phone', 'fax', 'email', 'registered_no', 'visibility');
        $shopLocation = ShopLocations::findOrFail($id);

        /** Start - Add shop location to database */
        $shopValidator = Validator::make($shop_vars, ShopLocations::rules('PATCH',$id), ShopLocations::$validationMessages, ShopLocations::$attributeNames);
        if ($shopValidator->fails()){
            return [
                'success'   => false,
                'title'     => 'Validation Error',
                'errors'    => $shopValidator->getMessageBag()->toArray()
            ];
        }

        $shopLocation->fill($shop_vars);
        $shopLocation->save();
        /** Stop - Add shop location to database */

        return [
            'success'   => true,
            'title'     => 'Store Details Updated',
            'message'   => 'Store details successfully updated... Page will reload'
        ];
    }

    public function store_address_update(Request $request, $id){
        if (!Auth::check()) {
            return [
                'success'   => false,
                'title'     => 'Login Error',
                'errors'    => 'You need to be logged in to access this function'
            ];
        }
        else{
            $user = Auth::user();
        }

        $address_vars = $request->only('address1', 'address2', 'city', 'postal_code', 'region');
        $shopLocation = ShopLocations::findOrFail($id);
        $shopAddress = Address::findOrFail($shopLocation->address_id);
        $address_vars['country_id'] = $shopAddress->country_id;

        /** Start - Update address to database */
        $addressValidator = Validator::make($address_vars, Address::rules('PATCH', $id), Address::$validationMessages, Address::$attributeNames);

        if ($addressValidator->fails()){
            return [
                'success'   => false,
                'title'     => 'Validation Error',
                'errors' => $addressValidator->getMessageBag()->toArray()
            ];
        }

        $shopAddress->fill($address_vars);
        $shopAddress->save();
        /** Stop - Update address to database */

        return [
            'success'   => true,
            'title'     => 'Store Details Updated',
            'message'   => 'Store details successfully updated... Page will reload'
        ];
    }

    public function shops_employee_working_plan(){
        $user = Auth::user();
        if (!$user || !$user->is_back_user()) {
            return redirect()->intended(route('admin/login'));
        }

        $shop_locations = ShopLocations::orderBy('name')->get();

        $breadcrumbs = [
            'Home'              => route('admin'),
            'Administration'    => route('admin'),
            'Shop'              => route('admin'),
            'Shop Locations'    => '',
        ];
        $text_parts  = [
            'title'     => 'Shops Working Plan',
            'subtitle'  => 'create / view / change working plan',
            'table_head_text1' => 'Backend Shops Working Plan'
        ];
        $sidebar_link= 'admin-backend-shops-employees-work-plan';

        return view('admin/shops/view_working_plan', [
            'breadcrumbs'   => $breadcrumbs,
            'text_parts'    => $text_parts,
            'in_sidebar'    => $sidebar_link,
            'shopLocations' => $shop_locations,
        ]);
    }

    public static function list_all_locations(){
        $user = Auth::user();
        if (!$user || !$user->is_back_user()) {
            return redirect()->intended(route('admin/login'));
        }

        $shop_locations = ShopLocations::orderBy('name')->get();

        return $shop_locations;
    }

    public function cash_terminals(){
        $user = Auth::user();
        if (!$user || !$user->is_back_user()) {
            return redirect()->intended(route('admin/login'));
        }

        $cash_terminals = CashTerminal::with('shopLocation')->get();
        $shops = ShopLocations::all();

        $breadcrumbs = [
            'Home'              => route('admin'),
            'Administration'    => route('admin'),
            'Back End User'     => route('admin'),
            'Permissions'        => '',
        ];
        $text_parts  = [
            'title'     => 'All Cash Terminals',
            'subtitle'  => 'add/edit/view terminals',
            'table_head_text1' => 'Backend Roles Permissions List'
        ];
        $sidebar_link= 'admin-backend-shops-cash_terminals';

        return view('admin/shops/all_cash_terminals', [
            'breadcrumbs' => $breadcrumbs,
            'text_parts'  => $text_parts,
            'in_sidebar'  => $sidebar_link,
            'cash_terminals' => $cash_terminals,
            'shops' => $shops,
        ]);
    }

    public function add_cash_terminal(Request $request){
        $user = Auth::user();
        if (!$user || !$user->is_back_user()) {
            return redirect()->intended(route('admin/login'));
        }

        $cashTerminalVars = $request->only('name', 'bar_code', 'location_id', 'status');
        $cashTerminalVars['status'] = 'active';

        /** Start - Add shop location to database */
        $cashTerminalValidator = Validator::make($cashTerminalVars, CashTerminal::rules('POST'), CashTerminal::$validationMessages, CashTerminal::$attributeNames);

        if ($cashTerminalValidator->fails()){
            //return $validator->errors()->all();
            return array(
                'success' => false,
                'errors' => $cashTerminalValidator->getMessageBag()->toArray()
            );
        }
        else{
            $cashTerminal = new CashTerminal();
            $cashTerminal->fill($cashTerminalVars);
            $cashTerminal->save();
        }
        /** Stop - Add shop location to database */

        return $cashTerminalVars;
    }

    public function add_new_store_resource(Request $request){
        $user = Auth::user();
        if (!$user || !$user->is_back_user()) {
            return redirect()->intended(route('admin/login'));
        }

        $shopResourceVars = $request->only('location_id', 'name', 'description', 'category_id', 'color_code');
        if ($shopResourceVars['color_code']==''){
            $shopResourceVars['color_code'] = '#6e6e6e';
        }
        /** Start - Add shop resource to database */
        $shopResourceValidator = Validator::make($shopResourceVars, ShopResource::rules('POST'), ShopResource::$validationMessages, ShopResource::$attributeNames);

        if ($shopResourceValidator->fails()){
            //return $validator->errors()->all();
            return [
                'success' => false,
                'title'   => 'There is an error',
                'errors'  => $shopResourceValidator->getMessageBag()->toArray()
            ];
        }
        else{
            $shopResource = new ShopResource();
            $shopResource->fill($shopResourceVars);
            $shopResource->save();

            return [
                'success' => true,
                'title'   => 'Resource Added',
                'message' => 'The resource '.$shopResourceVars['name'].' was added to the selected Shop Location'
            ];
        }
        /** Stop - Add shop resource to database */
    }

    public function all_inventory_make_transfer(){
        $user = Auth::user();
        if (!$user || !$user->is_back_user()) {
            return redirect()->intended(route('admin/login'));
        }

        $shop_locations = ShopLocations::get();
        foreach($shop_locations as $shop_loc){
            $var_addr = Address::find($shop_loc->address_id);
            if (isset($var_addr)){
                $all_address_fields = array(
                    $var_addr['attributes']['address1'],
                    $var_addr['attributes']['address2'],
                    $var_addr['attributes']['city'],
                    $var_addr['attributes']['postal_code'],
                    '<br />'.$var_addr['attributes']['region'],
                );

                $var_country = Countries::find($var_addr['attributes']['country_id']);
                $all_address_fields[] = $var_country->name;

                $shop_loc->full_address = implode(', ',$all_address_fields);
            }
        }

        $all_products = Product::orderBy('category_id')->get();
        $products = [];
        if ($all_products) {
            foreach ($all_products as $product) {
                $products[] = [
                    'name' => $product->name,
                    'id'   => $product->id,
                    'category_id' => $product->category_id
                ];
            }
        }

        $all_categories = ProductCategories::orderBy('name')->get();
        $categories = [];
        foreach($all_categories as $category){
            $categories[$category->id] = $category->name;
        }

        $breadcrumbs = [
            'Home'              => route('admin'),
            'Administration'    => route('admin'),
            'Shop'              => route('admin'),
            'Shop Locations'    => '',
        ];
        $text_parts  = [
            'title'     => 'Warehouse Inventory',
            'subtitle'  => 'stock and transfers',
            'table_head_text1' => 'Backend Shop Locations List'
        ];
        $sidebar_link= 'admin-backend-inventory-and-transfers';

        return view('admin/shops/stock_and_transfer', [
            'breadcrumbs'   => $breadcrumbs,
            'text_parts'    => $text_parts,
            'in_sidebar'    => $sidebar_link,
            'shopLocations' => $shop_locations,
            'products'      => $products,
            'categories'    => $categories
        ]);
    }
}
