<?php

namespace App\Http\Controllers;

use App\Address;
use App\Booking;
use App\CashTerminal;
use App\Product;
use App\ProductCategories;
use App\ShopLocations;
use App\ShopOpeningHour;
use App\ShopResource;
use App\ShopResourceCategory;
use App\ShopResourcePrice;
use App\VatRate;
use Carbon\Carbon;
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
        $vatRates = VatRate::all();

        $breadcrumbs = [
            'Home'                => route('admin'),
            'All Shops/Locations' => route('admin/shops/locations/all'),
            $shopDetails->name    => '',
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
            'vatRates'      => $vatRates
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

    public function get_shop_resource($id){
        $user = Auth::user();
        if (!$user || !$user->is_back_user()) {
            return redirect()->intended(route('admin/login'));
        }

        $resourceDetails = ShopResource::with('prices')->with('shop_location')->where('id','=',$id)->get()->first();
        if (!$resourceDetails){
            // return backend 404
        }

        $resourcePrices = [];
        if (sizeof($resourceDetails->prices)>0){
            foreach($resourceDetails->prices as $price){
                if ($price->date_start==NULL || $price->date_stop==null){
                    $date_interval = "no limit";
                }
                else{
                    $date_interval = Carbon::createFromFormat('Y-m-d', $price->date_start)->format('d-m-Y').' to '.Carbon::createFromFormat('Y-m-d', $price->date_stop)->format('d-m-Y');
                }
                $time_interval = Carbon::createFromFormat('H:i:s', $price->time_start)->format('H:i').' to '.Carbon::createFromFormat('H:i:s', $price->time_stop)->format('H:i');

                $days_in = '';
                $days = json_decode($price->days);
                foreach ($days as $day){
                    $days_in[] = jddayofweek($day-1, 1);
                }

                $resourcePrices[] = [
                    'id'    => $price->id,
                    'date_interval' => $date_interval,
                    'time_interval' => $time_interval,
                    'type'  => $price->type,
                    'price' => $price->price,
                    'days'  => implode(',',$days_in)
                ];
            }
        }

        $resourceCategories = ShopResourceCategory::orderBy('name','asc')->get();
        $shopResources = ShopResource::with('category')->with('prices')->where('location_id','=',$resourceDetails->location_id)->orderBy('category_id')->get();
        $vatRates = VatRate::all();

        $availablePrices = [];
        if ($shopResources){
            foreach($shopResources as $singleOne){
                if (sizeof($singleOne->prices)>0){
                    $availablePrices[] = ['id'=>$singleOne->id, 'name'=>$singleOne->name];
                }
            }
        }

        $breadcrumbs = [
            'Home'                                  => route('admin'),
            'All Shops/Locations'                   => route('admin/shops/locations/all'),
            $resourceDetails->shop_location->name   => route('admin/shops/locations/view', ['id'=>$resourceDetails->location_id]),
            $resourceDetails->name => '',
        ];
        $text_parts  = [
            'title'     => 'Resource Details',
            'subtitle'  => 'view resource details',
            'table_head_text1' => 'Backend Shop location resource Details'
        ];
        $sidebar_link= 'admin-backend-locations-resource-details-view';

        return view('admin/shops/get_location_resource', [
            'breadcrumbs'   => $breadcrumbs,
            'text_parts'    => $text_parts,
            'in_sidebar'    => $sidebar_link,
            'resourceDetails'   => $resourceDetails,
            'resourceCategory'  => $resourceCategories,
            'resourceList'  => $shopResources,
            'prices'        => $resourcePrices,
            'vatRates'      => $vatRates,
            'availablePrices'   => $availablePrices
        ]);
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

        $shopResourceVars = $request->only('location_id', 'name', 'description', 'category_id', 'color_code', 'session_price', 'vat_id');
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

    public function update_store_resource(Request $request){
        $user = Auth::user();
        if (!$user || !$user->is_back_user()) {
            return [
                'success' => false,
                'errors'  => 'You need to be logged in to access this function',
                'title'   => 'Error authentication'
            ];
        }

        $vars = $request->only('category_id', 'description', 'name', 'session_price', 'vat_rate', 'resource_id');
        $shopResource = ShopResource::where('id', '=', $vars['resource_id'])->get()->first();
        if (!$shopResource){
            return [
                'success' => false,
                'errors'  => 'We did not find the resource to update',
                'title'   => 'Error getting resource'
            ];
        }

        $ResourceVars = [
            'location_id'   => $shopResource->location_id,
            'name'          => $vars['name'],
            'description'   => $vars['description'],
            'category_id'   => $vars['category_id'],
            'session_price' => $vars['session_price'],
            'vat_id'        => $vars['vat_rate']
        ];
        $shopResourceValidator = Validator::make($ResourceVars, ShopResource::rules('PATCH', $shopResource->id), ShopResource::$validationMessages, ShopResource::$attributeNames);

        if ($shopResourceValidator->fails()){
            //return $validator->errors()->all();
            return [
                'success' => false,
                'title'   => 'There is an error',
                'errors'  => $shopResourceValidator->getMessageBag()->toArray()
            ];
        }
        else{
            $shopResource->fill($ResourceVars);
            $shopResource->save();

            return [
                'success' => true,
                'title'   => 'Resource updated',
                'message' => 'The resource '.$shopResource['name'].' was updated'
            ];
        }
    }

    public function delete_store_resource(Request $request){
        $user = Auth::user();
        if (!$user || !$user->is_back_user()) {
            return [
                'success' => false,
                'errors'  => 'You need to be logged in to access this function',
                'title'   => 'Error authentication'
            ];
        }

        $vars = $request->only('location_id', 'resource_id');

        $shopLocation = ShopLocations::where('id', '=', $vars['location_id'])->get()->first();
        if (!$shopLocation){
            return [
                'success' => false,
                'errors'  => 'We could not find the resource/location to delete',
                'title'   => 'Error deleting'
            ];
        }

        $shopResource = ShopResource::where('id', '=', $vars['resource_id'])->where('location_id','=',$shopLocation->id)->get()->first();
        if (!$shopResource){
            return [
                'success' => false,
                'errors'  => 'We could not find the resource/location to delete',
                'title'   => 'Error deleting'
            ];
        }

        $bookings = Booking::where('resource_id','=',$shopResource->id)->count();
        if ($bookings>0){
            return [
                'success' => false,
                'errors'  => 'Bookings were found for this resource; resource could not be deleted',
                'title'   => 'Bookings Found'
            ];
        }
        else{
            $shopResource->delete();
        }

        return [
            'success' => true,
            'title'   => 'Resource updated',
            'message' => 'The resource '.$shopResource['name'].' was updated'
        ];
    }

    public function get_resource_price_details(Request $request){
        $user = Auth::user();
        if (!$user || !$user->is_back_user()) {
            return [
                'success' => false,
                'errors'  => 'You need to be logged in to access this function',
                'title'   => 'Error authentication'
            ];
        }

        $vars = $request->only('price_id','resource_id');

        $price = ShopResourcePrice::where('id','=',$vars['price_id'])->where('resource_id','=',$vars['resource_id'])->get()->first();
        if (!$price){
            return['success'=>false, 'title'=>"Error", 'errors'=>"Resource price not found"];
        }
        else{
            $the_price = [
                'id'    => $price->id,
                'days'  => json_decode($price->days),
                'time_start' => Carbon::createFromFormat('H:i:s',$price->time_start)->format('H:i'),
                'time_stop'  => Carbon::createFromFormat('H:i:s',$price->time_stop)->format('H:i'),
                'date_start' => $price->date_start==""?"":Carbon::createFromFormat('Y-m-d', $price->date_start)->format('d-m-Y'),
                'date_stop'  => $price->date_stop==""?"":Carbon::createFromFormat('Y-m-d', $price->date_stop)->format('d-m-Y'),
                'type'  => $price->type,
                'price' => $price->price
            ];
            return ['success'=>true, 'price'=>$the_price];
        }
    }

    public function add_resource_price(Request $request){
        $user = Auth::user();
        if (!$user || !$user->is_back_user()) {
            return [
                'success' => false,
                'errors'  => 'You need to be logged in to access this function',
                'title'   => 'Error authentication'
            ];
        }

        $vars = $request->only('days', 'time_start', 'time_stop', 'date_start', 'date_stop', 'type', 'price', 'resource_id');

        if ($vars['type']=='general'){
            $date_start = '';
            $date_end   = '';
        }
        else{
            if ($vars['date_start'] == '' || $vars['date_stop'] == '') {
                $date_start = Carbon::now()->toDateString();
                $date_end   = Carbon::now()->toDateString();
            }
            else{
                $date_start = Carbon::createFromFormat('d-m-Y',$vars['date_start'])->toDateString();
                $date_end   = Carbon::createFromFormat('d-m-Y',$vars['date_stop'])->toDateString();
            }
        }

        $shopResource = ShopResource::with('vatRate')->where('id', '=', $vars['resource_id'])->get()->first();
        if (!$shopResource){
            return[];
        }

        $ResourcePriceVars = [
            'days'          => json_encode($vars['days']),
            'time_start'    => Carbon::createFromFormat('H:i',$vars['time_start'])->toTimeString(),
            'time_stop'     => Carbon::createFromFormat('H:i',$vars['time_stop'])->toTimeString(),
            'date_start'    => $date_start,
            'date_stop'     => $date_end,
            'type'          => $vars['type'],
            'price'         => $vars['price'],
            'vat_id'        => $shopResource->vatRate->id,
            'resource_id'   => $shopResource->id,
        ];

        if ($ResourcePriceVars['date_start']=='' || $ResourcePriceVars['date_stop']==''){
            unset($ResourcePriceVars['date_start']);
            unset($ResourcePriceVars['date_stop']);
        }

        $ResourcePriceValidator = Validator::make($ResourcePriceVars, ShopResourcePrice::rules('POST'), ShopResourcePrice::$validationMessages, ShopResourcePrice::$attributeNames);
        if ($ResourcePriceValidator->fails()){
            return [
                'success' => false,
                'title'   => 'There is an error',
                'errors'  => $ResourcePriceValidator->getMessageBag()->toArray()
            ];
        }
        else {
            $resourcePrice = new ShopResourcePrice();
            $resourcePrice->fill($ResourcePriceVars);
            $resourcePrice->save();

            return [
                'success' => true,
                'title'   => 'Resource Price Added',
                'message' => 'The resource price was added to the selected resource'
            ];
        }
    }

    public function update_resource_price(Request $request){
        $user = Auth::user();
        if (!$user || !$user->is_back_user()) {
            return [
                'success' => false,
                'errors'  => 'You need to be logged in to access this function',
                'title'   => 'Error authentication'
            ];
        }

        $vars = $request->only('days', 'time_start', 'time_stop', 'date_start', 'date_stop', 'type', 'price', 'resource_id', 'price_id');

        if ($vars['type']=='general'){
            $date_start = '';
            $date_end   = '';
        }
        else{
            if ($vars['date_start'] == '' || $vars['date_stop'] == '') {
                $date_start = Carbon::now()->toDateString();
                $date_end   = Carbon::now()->toDateString();
            }
            else{
                $date_start = Carbon::createFromFormat('d-m-Y',$vars['date_start'])->toDateString();
                $date_end   = Carbon::createFromFormat('d-m-Y',$vars['date_stop'])->toDateString();
            }
        }

        $shopResource = ShopResource::with('vatRate')->where('id', '=', $vars['resource_id'])->get()->first();
        if (!$shopResource){
            return[
                'success' => false,
                'errors'  => 'No resource found for selected price',
                'title'   => 'Error in update'];
        }

        $resourcePrice = ShopResourcePrice::where('id','=',$vars['price_id'])->where('resource_id','=',$shopResource->id)->get()->first();
        if (!$resourcePrice){
            return[
                'success' => false,
                'errors'  => 'No price object found for the given ID',
                'title'   => 'Error in update'];
        }

        $ResourcePriceVars = [
            'days'          => json_encode($vars['days']),
            'time_start'    => Carbon::createFromFormat('H:i',$vars['time_start'])->toTimeString(),
            'time_stop'     => Carbon::createFromFormat('H:i',$vars['time_stop'])->toTimeString(),
            'date_start'    => $date_start,
            'date_stop'     => $date_end,
            'type'          => $vars['type'],
            'price'         => $vars['price'],
            'vat_id'        => $resourcePrice->vat_id,
            'resource_id'   => $resourcePrice->resource_id,
        ];

        if ($ResourcePriceVars['date_start']=='' || $ResourcePriceVars['date_stop']==''){
            unset($ResourcePriceVars['date_start']);
            unset($ResourcePriceVars['date_stop']);
        }

        $ResourcePriceValidator = Validator::make($ResourcePriceVars, ShopResourcePrice::rules('PUT', $resourcePrice->id), ShopResourcePrice::$validationMessages, ShopResourcePrice::$attributeNames);
        if ($ResourcePriceValidator->fails()){
            return [
                'success' => false,
                'title'   => 'There is an error',
                'errors'  => $ResourcePriceValidator->getMessageBag()->toArray()
            ];
        }
        else {
            //xdebug_var_dump($ResourcePriceVars); exit;
            $resourcePrice->fill($ResourcePriceVars);
            $resourcePrice->save();

            return [
                'success' => true,
                'title'   => 'Resource Price Updated',
                'message' => 'The resource price was updated with success'
            ];
        }
    }

    public function delete_resource_price(Request $request){
        $user = Auth::user();
        if (!$user || !$user->is_back_user()) {
            return [
                'success' => false,
                'errors'  => 'You need to be logged in to access this function',
                'title'   => 'Error authentication'
            ];
        }

        $vars = $request->only('resource_id', 'price_id');

        $shopResource = ShopResource::with('vatRate')->where('id', '=', $vars['resource_id'])->get()->first();
        if (!$shopResource){
            return[
                'success' => false,
                'errors'  => 'No resource found for selected price',
                'title'   => 'Error in update'];
        }

        $resourcePrice = ShopResourcePrice::where('id','=',$vars['price_id'])->where('resource_id','=',$shopResource->id)->get()->first();
        if (!$resourcePrice){
            return[
                'success' => false,
                'errors'  => 'No price object found for the given ID',
                'title'   => 'Error in update'];
        }
        else{
            $resourcePrice->delete();
        }

        return [
            'success' => true,
            'title'   => 'Resource Price Deleted',
            'message' => 'The resource price was deleted with success'
        ];
    }

    public function copy_resource_prices(Request $request){
        $user = Auth::user();
        if (!$user || !$user->is_back_user()) {
            return [
                'success' => false,
                'errors'  => 'You need to be logged in to access this function',
                'title'   => 'Error authentication'
            ];
        }

        $vars = $request->only('from_resource_id', 'to_resource_id', 'location_id');

        $shopLocation = ShopLocations::where('id','=',$vars['location_id'])->get()->first();
        if (!$shopLocation){
            return[
                'success' => false,
                'errors'  => 'No location/resource pair found for given variables',
                'title'   => 'Error in update'];
        }

        $fromShopResource = ShopResource::where('id', '=', $vars['from_resource_id'])->where('location_id','=',$shopLocation->id)->get()->first();
        if (!$fromShopResource){
            return[
                'success' => false,
                'errors'  => 'No location/resource pair found for given variables',
                'title'   => 'Error in update'];
        }

        $toShopResource = ShopResource::where('id', '=', $vars['to_resource_id'])->where('location_id','=',$shopLocation->id)->get()->first();
        if (!$toShopResource){
            return[
                'success' => false,
                'errors'  => 'No location/resource pair found for given variables',
                'title'   => 'Error in update'];
        }

        $resourcePrices = ShopResourcePrice::where('resource_id','=',$fromShopResource->id)->get();
        if (!$resourcePrices){
            return[
                'success' => false,
                'errors'  => 'No prices object found for the given location/resource pair',
                'title'   => 'Error in update'];
        }
        else{
            foreach ($resourcePrices as $price){
                $newPrice = new ShopResourcePrice();
                $fill = [
                    'days'          => $price->days,
                    'time_start'    => $price->time_start,
                    'time_stop'     => $price->time_stop,
                    'date_start'    => $price->date_start,
                    'date_stop'     => $price->date_stop,
                    'type'          => $price->type,
                    'price'         => $price->price,
                    'vat_id'        => $price->vat_id,
                    'resource_id'   => $toShopResource->id,
                ];
                $newPrice->fill($fill);
                $newPrice->save();
                unset($newPrice);
            }
        }

        return [
            'success' => true,
            'title'   => 'Resource Prices Duplicated',
            'message' => 'The resource prices were duplicated with success'
        ];
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
