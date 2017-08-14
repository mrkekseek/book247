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
use App\ShopResourceCategory;
use App\UserBookedActivity;
use App\Booking;
use Auth;
use Snowfire\Beautymail\Beautymail;
use Illuminate\Http\Request;

class ApicController extends Controller
{
    const VERSION = '0.7.0';

    private static $message = [];
    private static $code;
    
    public function __construct()
    {
        
    }

    public function status(){
        return [
            'code' => 1,
            'version' => self::VERSION,
            'message' => [],
        ];
    }
    
    public function register_owner(Request $request)
    {
        $data = $request->only('first_name', 'middle_name', 'last_name', 'email_address', 'phone_number', 'dob', 'gender', 'country');

        $rules = [
            'first_name'    =>  'required|min:2|max:150',
            'middle_name'   =>  'min:2|max:150',
            'last_name'     =>  'required|min:2|max:150',
            'email_address' =>  'required|email|unique:users,username',
            'dob' =>  'required|date',
            'phone_number'  =>  'required|unique:personal_details,mobile_number',
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
        /*
        Validator::extend('unique_api_phonenumber', function($attribute, $value, $parameters, $validator) use($countries) {
            return ! ApiAuth::account_get_by_phone_number($value)['success'];
        });
        */
        $mesagges = [
            'country.exists_county' => 'Country not valid.',
            //'phone_number.unique_api_phonenumber' => 'User with this phone number allready registered on API SSO.'
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
            $password = substr(bcrypt(str_random(12)),0,8);
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
            
            $personalData = [
                'personal_email'=> $data['email_address'],
                'mobile_number' => $data['phone_number'],
                'date_of_birth' => Carbon::parse($data['dob'])->format('Y-m-d'),
                'bank_acc_no'   => 0,
                'social_sec_no' => 0,
                'about_info'    => '',                
            ];
            if (ApiAuth::checkExist($data['email_address'])['success'])
            {
                $apiUser = ApiAuth::accounts_get_by_username($data['email_address'])['data'];
                $dataForApi = $ownerData;
                $dataForApi['sso_user_id'] = $apiUser->id;
                $dataForApi['country_iso_3166_2'] = $country->iso_3166_2;
                $dataForApi['date_of_birth'] = $personalData['date_of_birth'];
                $dataForApi['mobile_number'] = $personalData['mobile_number'];
                unset($dataForApi['password']);
                if (Auth::update_api_user($dataForApi))
                {
                    $ownerData['sso_user_id'] = $apiUser->id;
                }
                else
                {
                    return [
                        'code' => 3,
                        'message' => 'Can not update user on API SSO. API SSO return: '. Auth::$error,
                    ];
                }
            }
            
            $owner = new User;
            $owner->fill($ownerData);
            if ($owner->save())
            {
                $owner->attachRole(1);
                $personalData['customer_number'] = $owner->get_next_customer_number();
                $personalData['user_id'] = $owner->id;
                $personalDetail = new PersonalDetail;
                $personalDetail->fill($personalData);
                if ($personalDetail->save())
                {
                    if (empty($owner->sso_user_id))
                    {
                        $this->send_mail_new_owner($owner, $password);
                    }
                    else
                    {
                        $this->send_mail_exist_owner($owner);
                    }
                    
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
    
    public function assign_subdomain_settings(Request $request)
    {
        $data = $request->only('account_key', 'club_details', 'club_address');
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
    
    public function get_all_locations_and_resources(Request $request)
    {
        $data = $request->only('account_key');
        $local_account_key = AppSettings::get_setting_value_by_name('globalWebsite_rankedin_integration_key');
        $rules = [
            'account_key'    =>  'required|in:'.$local_account_key,
        ];
        if ( ! $this->validate_request($data , $rules) )
        {
            $response = [
                'code' => self::$code,
                'message' => self::$message,
            ];
        }
        else
        {
            $locations = ShopLocations::with('address', 'resources', 'resources.category')->get();
            $arr_location = [];
            foreach ($locations as $item)
            {
                $location['name'] = $item->name;
                if ( ! empty($item->address))
                {
                    $country = Countries::find($item->address->country_id);
                    $location['full_address'] = $item->address->address1.' '.$item->address->address2.' '.$item->address->city.' '.$item->address->region.' '.$country->name;
                }
                else
                {
                    $location['full_address'] = '';
                }
                $location['resources'] = [];
                foreach ($item->resources as $res)
                {
                    $resorce['resource_name'] = $res->name;
                    $resorce['resource_type'] = $res->category->name;
                    $location['resources'][] = $resorce;
                }
                $arr_location[] = $location;
            }
            $response = [
                'code' => 1,
                'locations' => $arr_location,
                'message' => ''
            ];
        }
        return $response;
    }
    
    public function players_statistics_activity_gender_age(Request $request)
    {
        $data = $request->only('account_key', 'activity');
        //\Cache::forget('globalWebsite_rankedin_integration_key');
        $local_account_key = AppSettings::get_setting_value_by_name('globalWebsite_rankedin_integration_key');
        $rules = [
            'account_key'    =>  'required|in:'.$local_account_key,
            'activity'    =>  'required|integer|activity_check'
        ];
        
        Validator::extend('activity_check', function($attribute, $value, $parameters, $validator){
            $activities = ShopResourceCategory::select('id')->get();
            if ($value == -1) return TRUE;
            foreach($activities as $item)
            {
                if ($item->id == $value) return TRUE;
            }
            return FALSE;
        });
        $mesagges = [
            'activity.activity_check' => 'Activity not valid.',
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
            $query = UserBookedActivity::query();
            if ($data['activity'] != -1)
            {
                $query->where('activity_id', $data['activity']);
            }
            $userBookedActivity = $query->with(['activities','users', 'users.PersonalDetail'])->get();
            $age_array = [];
            foreach ($userBookedActivity as $item)
            {
                if ( ! empty ($item->users->PersonalDetail->date_of_birth))
                {
                    $text_gender = $item->users->gender == 'F' ? 'female' : 'male'; 
                    $dob = new Carbon($item->users->PersonalDetail->date_of_birth);
                    $age =  $dob->diffInYears(Carbon::now());
                    if ( ! isset($age_array[$item->activity_id][$text_gender][$age]))
                    {
                        $age_array[$item->activity_id][$text_gender][$age] = 0;
                    }
                    $age_array[$item->activity_id][$text_gender][$age]++;
                }
            }
            $response = [
                'code' => 1,
                'players' => $age_array,
                'message' => ''
            ];
        }
        return $response;
    }
    
    public function get_latest_registered_players(Request $request)
    {
        $data = $request->only('account_key', 'activity', 'time_interval');
        //\Cache::forget('globalWebsite_rankedin_integration_key');
        $local_account_key = AppSettings::get_setting_value_by_name('globalWebsite_rankedin_integration_key');
        $rules = [
            'account_key'    =>  'required|in:'.$local_account_key,
            'activity'    =>  'required|integer|activity_check',
            'time_interval' => 'integer|in:7,14,30'
        ];
        
        Validator::extend('activity_check', function($attribute, $value, $parameters, $validator){
            $activities = ShopResourceCategory::select('id')->get();
            if ($value == -1) return TRUE;
            foreach($activities as $item)
            {
                if ($item->id == $value) return TRUE;
            }
            return FALSE;
        });
        $mesagges = [
            'activity.activity_check' => 'Activity not valid.',
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
            $time_interval = isset($data['time_interval']) ? $data['time_interval'] : 7; 
            $query = UserBookedActivity::query();
            if ($data['activity'] != -1)
            {
                $query->where('activity_id', $data['activity']);
            }
            $minDate = Carbon::now()->subDay($time_interval);
            $query->whereDate('created_at', '>=', $minDate);
            $countPlayers = $query->count();
            $response = [
                'code' => 1,
                'players' => $countPlayers,
                'message' => ''
            ];
        }
        return $response;
    }
    
    public function get_members_growth(Request $request)
    {
        $data = $request->only('account_key', 'activity', 'time_interval', 'intervals');
        $local_account_key = AppSettings::get_setting_value_by_name('globalWebsite_rankedin_integration_key');
        $rules = [
            'account_key'    =>  'required|in:'.$local_account_key,
            'activity'    =>  'required|integer|activity_check',
            'time_interval' => 'integer|in:1,7,30',
            'intervals' => 'integer'
        ];
        
        Validator::extend('activity_check', function($attribute, $value, $parameters, $validator){
            $activities = ShopResourceCategory::select('id')->get();
            if ($value == -1) return TRUE;
            foreach($activities as $item)
            {
                if ($item->id == $value) return TRUE;
            }
            return FALSE;
        });
        $mesagges = [
            'activity.activity_check' => 'Activity not valid.',
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
            $time_interval = isset($data['time_interval']) ? $data['time_interval'] : 7; 
            $intervals = isset($data['intervals']) && ! empty($data['intervals']) ? $data['intervals'] : 30;
            $minDate = Carbon::now()->subDay($time_interval);
            $query = UserBookedActivity::query();
            if ($data['activity'] != -1)
            {
                $query->where('activity_id', $data['activity']);
            }
            $query->whereDate('created_at', '>=', $minDate);
            $query->whereHas('users.membership', function ($q) use ($intervals){
                $q->where('invoice_period', $intervals);
            });
            $userBookedActivity = $query->with(['users'])->get();
            $players = [
                'paying_members' => 0,
                'non_paying_members' => 0,
            ];
            foreach ($userBookedActivity as $item)
            {
                if ( ! empty($item->users->get_active_membership()))
                {
                    $players['paying_members']++;
                }
                else
                {
                    $players['non_paying_members']++;
                }
            }
            $response = [
                'code' => 1,
                'players' => $players,
                'message' => ''
            ];
        }
        return $response;
    }
    
    public function get_bookings_per_parts_of_day (Request $request)
    {
        $data = $request->only('account_key', 'activity', 'time_interval', 'intervals');
        $local_account_key = AppSettings::get_setting_value_by_name('globalWebsite_rankedin_integration_key');
        $rules = [
            'account_key'    =>  'required|in:'.$local_account_key,
            'activity'    =>  'required|integer|activity_check',
            'time_interval' => 'integer|in:1,7,30,90,180,360',
        ];
        
        Validator::extend('activity_check', function($attribute, $value, $parameters, $validator){
            $activities = ShopResourceCategory::select('id')->get();
            if ($value == -1) return TRUE;
            foreach($activities as $item)
            {
                if ($item->id == $value) return TRUE;
            }
            return FALSE;
        });
        $mesagges = [
            'activity.activity_check' => 'Activity not valid.',
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
            $time_interval = isset($data['time_interval']) ? $data['time_interval'] : 180;
            $minDate = Carbon::now()->subDay($time_interval);
            $query = Booking::query();
            if ($data['activity'] != -1)
            {
                $category_id = $data['activity'];
                $query->whereHas('resource', function ($q) use ($category_id) {
                    $q->where('category_id', $category_id);
                });
            }
            $query->whereDate('created_at', '>=', $minDate);
            $bookings = $query->get();
            $result = [
                'morning' => 0,
                'afternoon' => 0,
                'evening' => 0,
                'night' => 0,
            ];
            $m_start = Carbon::createFromTime('05','00');
            $m_end = Carbon::createFromTime('12','00');
            $a_start = Carbon::createFromTime('12','00');
            $a_end = Carbon::createFromTime('17','00');
            $e_start = Carbon::createFromTime('17','00');
            $e_end = Carbon::createFromTime('21','00');
            $n_start = Carbon::createFromTime('21','00');
            $n_end = Carbon::createFromTime('04','00');
            foreach ($bookings as $item)
            {
                $booking_time_start = new Carbon($item->booking_time_start);
                $booking_time_stop = new Carbon($item->booking_time_stop);
                switch (TRUE)
                {
                    case ($booking_time_start->between($m_start, $m_end)):
                        $result['morning']++; 
                        break;
                    case ($booking_time_start->between($a_start, $a_end)):
                        $result['afternoon']++; 
                        break;
                    case ($booking_time_start->between($e_start, $e_end)):
                        $result['evening']++; 
                        break;
                    case ($booking_time_start->between($n_start, $n_end)):
                        $result['night']++; 
                        break;
                }
            }
            $response = [
                'code' => 1,
                'bookings' => $result,
                'message' => ''
            ];
        }
        return $response;
    }

    private function send_mail_exist_owner($owner)
    {
        $shop_location = ShopLocations::first();
        $data = [
            'first_name' => $owner->first_name,
            'last_name' => $owner->last_name,
            'email' => $owner->email,
            'club_name' => $shop_location->name,
        ];

        $template = EmailsController::build('Registering an existing owner', $data);
        if ($template)
        {
            $main_message = $template["message"];
            $subject = AppSettings::get_setting_value_by_name('globalWebsite_email_company_name_in_title') . ' - Online Booking System - You are registered!';
            $beauty_mail = app()->make(Beautymail::class);
            $beauty_mail->send('emails.email_default_v2',
                ['body_message' => $main_message, 'user' => $owner],
                function($message) use ($owner) {
                    $message
                            ->from(AppSettings::get_setting_value_by_name('globalWebsite_system_email'))
                            ->to($owner->email, $owner->first_name.' '.$owner->middle_name.' '.$owner->last_name)
                            ->subject(AppSettings::get_setting_value_by_name('globalWebsite_email_company_name_in_title').' - Online Booking System - You are registered!');
                });
        }
    }
    
    private function send_mail_new_owner($owner, $password)
    {
        $shop_location = ShopLocations::first();
        $data = [
            'first_name' => $owner->first_name,
            'last_name' => $owner->last_name,
            'email' => $owner->email,
            'club_name' => $shop_location->name,
            'password' => $password
        ];

        $template = EmailsController::build('Registration of new owner', $data);
        if ($template)
        {
            $main_message = $template["message"];
            $subject = AppSettings::get_setting_value_by_name('globalWebsite_email_company_name_in_title') . ' - Online Booking System - You are registered!';
            $beauty_mail = app()->make(Beautymail::class);
            $beauty_mail->send('emails.email_default_v2',
                ['body_message' => $main_message, 'user' => $owner],
                function($message) use ($owner) {
                    $message
                            ->from(AppSettings::get_setting_value_by_name('globalWebsite_system_email'))
                            ->to($owner->email, $owner->first_name.' '.$owner->middle_name.' '.$owner->last_name)
                            ->subject(AppSettings::get_setting_value_by_name('globalWebsite_email_company_name_in_title').' - Online Booking System - You are registered!');
                });
        }
    }


    private function validate_request($data, $rules, $mesagges = [])
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
