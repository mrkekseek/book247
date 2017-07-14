<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Webpatser\Countries\Countries;
use App\Http\Requests;
use Auth;

use App\ShopLocations;
use App\Address;
use App\ShopLocationCategoryIntervals;
use App\ShopResourceCategory;
use App\MembershipRestriction;
use App\MembershipPlan;

class RegistrationStepsController extends Controller
{
   	public function index()
   	{
        $status = AppSettings::get_setting_value_by_name('globalWebsite_registration_finished');
        if ( $status == 0 )
        {
            return redirect('');
        }
        $countries = Countries::orderBy('name', 'asc')->get();
		$currencies = Countries::groupBy('currency_code')->get();
        $shopLocation = ShopLocations::first();
        $shopResourceCategories = ShopResourceCategory::get();
        $user = Auth::user();
		return view('registration-form', [
            'countries'=>$countries, 
            'currencies'=>$currencies, 
            'shopLocation'=>$shopLocation,
            'shopResourceCategories'=>$shopResourceCategories,
            'user'=>$user,
            ]);
   	}
    
    public function save(Request $request)
    {
        $status = AppSettings::get_setting_value_by_name('globalWebsite_registration_finished');
        if ( $status == 0 )
        {
            $response = [
                'success' => FALSE,
            ];
        }
        else
        {
            $user = Auth::user();    
            $data = $request->only('clubname','email','phone','fax','addressline1','addressline2','city','region','postalcode','country','currency','sport','time','members','pay','resource','day','limit');
            $shopLocation = ShopLocations::first();
            $shopLocation->phone = $data['phone'];
            $shopLocation->fax = $data['fax'];
            $shopLocation->email = $data['email'];
            $shopLocation->save();
            
            $address = Address::find($shopLocation->address_id);
            $address->user_id = $user->id;
            $address->address1 = $data['addressline1'];
            $address->address2 = $data['addressline2'];
            $address->city = $data['city'];
            $address->region = $data['region'];
            $address->postal_code = $data['postalcode'];
            $address->country_id = $data['country'];
            $address->save();
            
            AppSettings::update_settings_value_by_name('finance_currency', $data['currency']);
            
            $shopLocationCategoryIntervals = new ShopLocationCategoryIntervals;
            $shopLocationCategoryIntervals->location_id = $shopLocation->id;
            $shopLocationCategoryIntervals->category_id = $data['sport'];
            $shopLocationCategoryIntervals->time_interval = $data['time'];
            $shopLocationCategoryIntervals->is_locked = 0;
            $shopLocationCategoryIntervals->added_by = $user->id;
            $shopLocationCategoryIntervals->save();
            
            $membershipPlan = MembershipPlan::first();
            if ( empty ($membershipPlan))
            {
                $membershipPlan = new MembershipPlan();
                $membershipPlan->name = $data['clubname'];
                $membershipPlan->save();
            }
            
            $activity = ShopResourceCategory::find($shopLocationCategoryIntervals->category_id);
            if ( ! empty($data['members']))
            {
                $membershipRestriction = new MembershipRestriction;
                $membershipRestriction->membership_id = 1;
                $membershipRestriction->restriction_id = 5;
                $membershipRestriction->value = json_encode(['activity'=>$activity->name]);
                $membershipRestriction->save();
            }
            $membershipRestriction = new MembershipRestriction;
            $membershipRestriction->membership_id = 1;
            $membershipRestriction->restriction_id = 6;
            $membershipRestriction->max_value = $data['day']*24;
            $membershipRestriction->save();
            AppSettings::update_settings_value_by_name('bookings_upfront_reservation_rule', $data['day']);
            
            $membershipRestriction = new MembershipRestriction;
            $membershipRestriction->membership_id = 1;
            $membershipRestriction->restriction_id = 3;
            $membershipRestriction->value = $data['limit'];
            $membershipRestriction->save();
            AppSettings::update_settings_value_by_name('bookings_cancellation_before_time_rule', $data['limit']);
            switch ($data['pay'])
            {
                case (0):
                    $allowed_settings_id = 8;
                    break;
                case (1):
                    $allowed_settings_id = 7;
                    break;
            }
            AppSettings::update_settings_value_by_name('bookings_online_payment_rule', $allowed_settings_id);
            
            switch ($data['resource'])
            {
                case (0):
                    $allowed_settings_id = 6;
                    break;
                case (1):
                    $allowed_settings_id = 5;
                    break;
            }
            AppSettings::update_settings_value_by_name('show_calendar_availability_rule', $allowed_settings_id);
            $response = [
                'success' => TRUE,
            ];
            
            AppSettings::update_settings_value_by_name('globalWebsite_registration_finished', 20);
            
            AppSettings::clear_cache();
        }
        return $response;
    }

}
