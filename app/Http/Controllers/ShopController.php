<?php

namespace App\Http\Controllers;

use App\Address;
use App\CashTerminal;
use App\ShopLocations;
use App\ShopOpeningHour;
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
        if (!Auth::check()) {
            return redirect()->intended(route('admin/login'));
        }
        else{
            $user = Auth::user();
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
            return redirect()->intended(route('admin/login'));
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
                'success' => false,
                'errors' => $shopValidator->getMessageBag()->toArray()
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
                'success' => false,
                'errors' => $addressValidator->getMessageBag()->toArray()
            );
        }
        else{
            $shopAddress = new Address();
            $shopAddress->fill($address_vars);
            $shopAddress->save();

            $shopLocation->address_id = $shopAddress->id;
            $shopLocation->save();
        }
        /** Stop - Add address to database */

        return $shop_vars;
    }

    public function get_shop_location($id){
        if (!Auth::check()) {
            return redirect()->intended(route('admin/login'));
        }
        else{
            $user = Auth::user();
        }

        $shopDetails = ShopLocations::find($id);
        $shopAddress = Address::find($shopDetails->address_id);

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
            'weekDays'      => $weekDays
        ]);
    }

    public function view_shop_location(){
        if (!Auth::check()) {
            return redirect()->intended(route('admin/login'));
        }
        else{
            $user = Auth::user();
        }


    }

    public function update_opening_hours(Request $request, $id){
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

        return 'bine';
    }

    public function store_details_update(Request $request, $id){
        $shop_vars = $request->only('name', 'bank_acc_no', 'phone', 'fax', 'email', 'registered_no');
        $shopLocation = ShopLocations::findOrFail($id);

        /** Start - Add shop location to database */
        $shopValidator = Validator::make($shop_vars, ShopLocations::rules('PATCH',$id), ShopLocations::$validationMessages, ShopLocations::$attributeNames);

        if ($shopValidator->fails()){
            //return $validator->errors()->all();
            return array(
                'success' => false,
                'errors' => $shopValidator->getMessageBag()->toArray()
            );
        }
        $shopLocation->fill($shop_vars);
        $shopLocation->save();
        /** Stop - Add shop location to database */

        return $shop_vars;
    }

    public function store_address_update(Request $request, $id){
        $address_vars = $request->only('address1', 'address2', 'city', 'postal_code', 'region');
        $shopLocation = ShopLocations::findOrFail($id);
        $shopAddress = Address::findOrFail($shopLocation->address_id);
        $address_vars['country_id'] = $shopAddress->country_id;

        /** Start - Update address to database */
        $addressValidator = Validator::make($address_vars, Address::rules('PATCH', $id), Address::$validationMessages, Address::$attributeNames);

        if ($addressValidator->fails()){
            return array(
                'success' => false,
                'errors' => $addressValidator->getMessageBag()->toArray()
            );
        }

        $shopAddress->fill($address_vars);
        $shopAddress->save();
        /** Stop - Update address to database */

        return $address_vars;
    }

    public function shops_employee_working_plan(){
        if (!Auth::check()) {
            return redirect()->intended(route('admin/login'));
        }
        else{
            $user = Auth::user();
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
        if (!Auth::check()) {
            return redirect()->intended(route('admin/login'));
        }
        else{
            $user = Auth::user();
        }

        $shop_locations = ShopLocations::orderBy('name')->get();

        return $shop_locations;
    }

    public function cash_terminals(){
        if (!Auth::check()) {
            return redirect()->intended(route('admin/login'));
        }
        else{
            $user = Auth::user();
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
            'title'     => 'All Permissions',
            'subtitle'  => 'add/edit/view permissions',
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
        if (!Auth::check()) {
            return redirect()->intended(route('admin/login'));
        }
        else{
            $user = Auth::user();
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
}
