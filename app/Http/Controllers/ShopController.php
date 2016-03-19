<?php

namespace App\Http\Controllers;

use App\Address;
use App\ShopLocations;
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

        $shopDetails = ShopLocations::find($id);
        $shopAddress = Address::find($shopDetails->address_id);

        $addressCountry = Countries::find($shopAddress->country_id);
        $shopAddress->countryName = $addressCountry->name;

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
            'shopDetails' => $shopDetails,
            'shopAddress' => $shopAddress,
        ]);
    }

    public function view_shop_location(){

    }

    public function shops_employee_working_plan(){
        if (!Auth::check()) {
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
}
