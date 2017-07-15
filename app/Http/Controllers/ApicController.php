<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Validator;
use Webpatser\Countries\Countries;
use App\Http\Libraries\ApiAuth;
use App\User;
use App\PersonalDetail;
use App\Address;
use App\ShopLocations;
use App\applicationSetting as ApplicationSettings;
use App\Settings;

class ApicController extends Controller
{
    const VERSION = '0.6.0';

    private static $message = [];
    private static $code;
    
    public function __construct()
    {
        
    }

    public function index($method, $id = FALSE)
    {
        \Debugbar::disable();
        $response = [];
        $request_method = strtolower(request()->method());
        $func = strtolower($method).(ucfirst($request_method));
        $data = request()->all();
        if (method_exists($this, $func))
        {
            //$response = $this->callAction($func, ['data' => $data]);
            $response = $this->$func($data);
        }
        else
        {
            $response = [
                'code' => 5,
                'message' => ['Method not fount.'],
            ];
            
        }
        $response = json_encode($response, JSON_NUMERIC_CHECK);
        return response($response);
    }
    
    private function statusGet(){
        return [
            'code' => 1,
            'version' => self::VERSION,
            'message' => [],
        ];
    }
    
    public function register_ownerPOST($data)
    {
        $data = array_only($data, ['first_name', 'middle_name', 'last_name', 'email_address', 'phone_number', 'dob', 'gender', 'country']);
        //$password = substr(bcrypt(str_random(12)),0,8);
        $password = '111111111';
        $rules = [
            'first_name'    =>  'required|min:2|max:150',
            'middle_name'   =>  'min:2|max:150',
            'last_name'     =>  'required|min:2|max:150',
            'email_address' =>  'required|email|unique:users,username|unique_api_username',
            'dob' =>  'required|date',
            'phone_number'  =>  'required|unique:personal_details,mobile_number|unique_api_phonenumber',
            'gender'        =>  'required|in:M,F',
            'country'        =>  'required|size:2|exists_county',
        ];
        
        $countries = Countries::get();
        Validator::extend('exists_county', function($attribute, $value, $parameters, $validator) use($countries) {
            foreach ($countries as $item)
            {
                if (strtolower($item->iso_3166_2) == strtolower($value))
                {
                    return TRUE;
                    
                }
            }
            return FALSE;
        });
        
        Validator::extend('unique_api_username', function($attribute, $value, $parameters, $validator) use($countries) {
            return ! ApiAuth::checkExist($value)['success'];
        });
        Validator::extend('unique_api_phonenumber', function($attribute, $value, $parameters, $validator) use($countries) {
            return ! ApiAuth::account_get_by_phone_number($value)['success'];
        });
        
        $mesagges = [
            'country.exists_county' => 'Country not valid.',
            'email_address.unique_api_username' => 'User with this email allready registered on API SSO.',
            'phone_number.unique_api_phonenumber' => 'User with this phone number allready registered on API SSO.'
        ];
        if ( ! $this->validate_request($data , $rules, $mesagges) )
        {
            $response = [
                'code' => self::$code,
                'message' => self::$message,
            ];
        }
        else
        {
            $country = Countries::where('iso_3166_2',$data['country'])->first();
            $ownerData = [
                'first_name'    => $data['first_name'],
                'middle_name'   => (isset($data['middle_name']) && ! empty($data['middle_name'])) ? $data['middle_name'] : '',
                'last_name'     => $data['last_name'],
                'username'      => $data['email_address'],
                'email'         => $data['email_address'],
                'password'      => bcrypt($password),
                'country_id'    => $country->id,
                'gender'        => $data['gender'],
                'status'        => 'active',
            ];
            $owner = new User;
            $owner->fill($ownerData);
            
            if ($owner->save())
            {
                $owner->attachRole(1);
                $personalData = [
                    'personal_email'=> $data['email_address'],
                    'mobile_number' => $data['phone_number'],
                    'date_of_birth' => Carbon::parse($data['dob'])->format('Y-m-d'),
                    'bank_acc_no'   => 0,
                    'social_sec_no' => 0,
                    'about_info'    => '',                
                    'customer_number'   => $owner->get_next_customer_number(),
                    'user_id'   => $owner->id
                ];
                $personalDetail = new PersonalDetail;
                $personalDetail->fill($personalData);
                if ($personalDetail->save())
                {
                    $response = [
                        'code' => 1,
                        'message' => 'User created.',
                    ];
                }
            }
            else
            {
                $response = [
                    'code' => 4,
                    'message' => 'Something went wrong.',
                ];
            }
        }
        return $response;
    }
    
    private function assign_subdomain_settingsPost($data)
    {
        $data = array_only($data, ['account_key', 'club_details', 'club_address']);
        //$key = AppSettings::get_setting_value_by_name('globalWebsite_rankedin_integration_key');
        $rules = [
            'club_details.club_name'    =>  'required|min:2|max:150',
            'club_address.address1' => 'required|min:2|max:150',
            'club_address.country' => 'size:2|exists_county',
        ];
        $mesagges = [
            'club_address.country.exists_county' => 'Country not valid.',
        ];
        $countries = Countries::get();
        Validator::extend('exists_county', function($attribute, $value, $parameters, $validator) use($countries) {
            foreach ($countries as $item)
            {
                if (strtolower($item->iso_3166_2) == strtolower($value))
                {
                    return TRUE;
                    
                }
            }
            return FALSE;
        });
        
        if ( ! $this->validate_request($data , $rules, $mesagges) )
        {
            $response = [
                'code' => self::$code,
                'message' => self::$message,
            ];
        }
        else
        {
            if (isset($data['club_address']['country']) && !empty($data['club_address']['country']))
            {
                $country = Countries::where('iso_3166_2',$data['club_address']['country'])->first();
                $country_id = $country->id;
            }
            else
            {
                $country_id = AppSettings::get_setting_value_by_name('globalWebsite_defaultCountryId');
            }
            $addressData = [
                'address1' => ! empty($data['club_address']['address1']) ? $data['club_address']['address1'] : '',
                'address2' => ! empty($data['club_address']['address2']) ? $data['club_address']['address2'] : '',
                'city' => ! empty($data['club_address']['city']) ? $data['club_address']['city'] :'',
                'region' => ! empty($data['club_address']['region']) ? $data['club_address']['region'] : '',
                'postal_code' => ! empty($data['club_address']['zip_code']) ? $data['club_address']['zip_code'] : '',
                'country_id'    => $country_id,
            ];
            $shopAddress = new Address();
            $shopAddress->fill($addressData);
            if ($shopAddress->save())
            {
                $shopLocationData = [
                    'address_id' => $shopAddress->id,
                    'name' =>  $data['club_details']['club_name'],
                    'visibility' =>  'public'
                ];
                $shopLocation = new ShopLocations();
                $shopLocation->fill($shopLocationData);
                $shopLocation->save();
                if ($shopLocation->save())
                {
                    $setting = Settings::where('system_internal_name','globalWebsite_rankedin_integration_key')->first();
                    $setting_value= ApplicationSettings::where('setting_id',$setting->id)->first();
                    $setting_value->unconstrained_value = $data['account_key'];
                    if($setting_value->save()){
                        $response = [
                            'code' => 1,
                            'message' => 'Shop location created.',
                        ];
                    } else {
                        $response = [
                            'code' => 4,
                            'message' => 'Something went wrong(account_key).',
                        ];
                    }

                }
            }
            else
            {
                $response = [
                    'code' => 4,
                    'message' => 'Something went wrong.',
                ];
            }
        }
        return $response;
    }
    
        
      
    private function validate_request($data, $rules, $mesagges)
    {
        $validator = Validator::make($data, $rules, $mesagges);
        if ($validator->fails())
        {
            foreach ($validator->errors()->all() as $error)
            {
                self::$message[] = $error;
            }
            self::$code = 3;
            return FALSE;
        }
        return TRUE;
    }
    
}
