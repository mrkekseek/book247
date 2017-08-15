<?php
namespace App\Http\Controllers\Federation;

use App\Http\Controllers\Controller;
use App\User;
use App\UserMembership;
use Illuminate\Http\Request;
use App\Http\Controllers\RequestValidator;
use App\User as UserModel;
use App\IframePermission;
use League\Flysystem\Exception;
use Validator;
use Webpatser\Countries\Countries;
use App\Http\Libraries\ApiAuth;
use App\PersonalDetail;
use App\Address;
use App\ShopLocations;
use Carbon\Carbon;
use App\Http\Libraries\Auth;
use Snowfire\Beautymail\Beautymail;


class FederationApi extends Controller {

    const VERSION= '0.1';

    /**
     * @param Request $r
     * @return array
     */
    public function status(Request $r)
    {
        if (RequestValidator::validate($r)) {
            return json_encode([
                'code' => 1,
                'version' => self::VERSION
            ]);
        } else {
            return json_encode([
                'code' => 2,
                'version' => "Permission denied"
            ]);
        }
    }

    /**
     * @param $country
     * @return bool
     */
    private function validate_country($country) {
        $countries = Countries::get();
        foreach ($countries as $item)
        {
            if (strtolower($item->iso_3166_2) == strtolower($country))
            {
                return true;

            }
        }
        return false;
    }

    public function register_owner(Request $r)
    {
        if (RequestValidator::validate($r)) {
            $data = $r->only(['first_name', 'middle_name', 'last_name', 'email_address', 'phone_number', 'dob', 'gender', 'country']) ;
            if (sizeof($data) != 8) {
                return json_encode([
                    'code' => 3,
                    'message' => "Invalid data"
                ]);
            }
            foreach ($data as $d) {
                if (!isset($d)) {
                    return json_encode([
                        'code' => 3,
                        'message' => "Invalid data"
                    ]);
                }
            }

            if (!$this->validate_country($data['country'])) {
                return json_encode([
                    'code' => 3,
                    'message' => "Invalid data"
                ]);
            }

            if (ApiAuth::checkExist($data['email_address'])['success']) {
                return json_encode([
                    'code' => 3,
                    'message' => "User doesn't exist"
                ]);
            }

            if (ApiAuth::account_get_by_phone_number($data['phone_number'])['success']) {
                return json_encode([
                    'code' => 3,
                    'message' => "User already exists"
                ]);
            }
            $password = substr(str_random(12),0,8);
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
            //$owner->save()
            if($owner->save()) {
                $owner->attachRole(1);
                $personalData['customer_number'] = $owner->get_next_customer_number();
                $personalData['user_id'] = $owner->id;
                $personalDetail = new PersonalDetail;
                $personalDetail->fill($personalData);
                // $personalDetail->save()
                if ($personalDetail->save())
                {
                    if (empty($owner->sso_user_id)) {
                        $this->send_mail_new_owner($owner, $password);
                    } else {
                        $this->send_mail_exist_owner($owner);
                    }

                    return json_encode([
                        'code' => 1,
                        'message' => 'User created.',
                    ]);
                }
            } else {
                return json_encode([
                    'code' => 4,
                    'message' => 'User exists.',
                ]);
            }


        } else {
            return json_encode([
                'code' => 2,
                'message' => "Permission denied"
            ]);
        }
    }

    public function assign_subdomain_settings (Request $r)
    {
        if (RequestValidator::validate($r)) {
            $data = $r->only(['account_key', 'club_details', 'club_address']);
            if (sizeof($data) != 3) {
                return json_encode([
                    'code' => 3,
                    'message' => "Invalid data"
                ]);
            }
            foreach ($data as $d) {
                if (!isset($d)) {
                    return json_encode([
                        'code' => 3,
                        'message' => "Invalid data"
                    ]);
                }
            }
            if (!$this->validate_country($data['country'])) {
                return json_encode([
                    'code' => 3,
                    'message' => "Invalid data"
                ]);
            }

            if (isset($data['club_address']['country']) && !empty($data['club_address']['country'])) {
                $country = Countries::where('iso_3166_2',$data['club_address']['country'])->first();
                $country_id = $country->id;
            } else {
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
                        return json_encode([
                            'code' => 1,
                            'message' => 'Shop location created.',
                        ]);
                    } else {
                        return json_encode([
                            'code' => 4,
                            'message' => 'Something went wrong(account_key).',
                        ]);
                    }

                }
            }
            else
            {
                return json_encode([
                    'code' => 4,
                    'message' => 'Something went wrong.',
                ]);
            }


        } else {
            return json_encode([
                'code' => 2,
                'message' => "Permission denied"
            ]);
        }


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
        if ($template) {
            $main_message = $template["message"];
            $subject = AppSettings::get_setting_value_by_name('globalWebsite_email_company_name_in_title') . ' - Online Booking System - You are registered!';
            $beauty_mail = app()->make(Beautymail::class);
            $beauty_mail->send(
                'emails.email_default_v2',
                [
                    'body_message' => $main_message,
                    'user' => $owner
                ],
                function($message) use ($owner) {
                    $message
                        ->from(AppSettings::get_setting_value_by_name('globalWebsite_system_email'))
                        ->to($owner->email, $owner->first_name.' '.$owner->middle_name.' '.$owner->last_name)
                        ->subject(AppSettings::get_setting_value_by_name('globalWebsite_email_company_name_in_title').' - Online Booking System - You are registered!');
                }
            );
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
        if ($template) {
            $main_message = $template["message"];
            $subject = AppSettings::get_setting_value_by_name('globalWebsite_email_company_name_in_title') . ' - Online Booking System - You are registered!';
            $beauty_mail = app()->make(Beautymail::class);
            $beauty_mail->send(
                'emails.email_default_v2',
                [
                    'body_message' => $main_message,
                    'user' => $owner
                ],
                function($message) use ($owner) {
                    $message
                        ->from(AppSettings::get_setting_value_by_name('globalWebsite_system_email'))
                        ->to($owner->email, $owner->first_name.' '.$owner->middle_name.' '.$owner->last_name)
                        ->subject(AppSettings::get_setting_value_by_name('globalWebsite_email_company_name_in_title').' - Online Booking System - You are registered!');
                });
        }
    }

    public function federation_member_has_valid_license(Request $r)
    {
        if(RequestValidator::validate($r)){
            if ( $r->get('memberSSOid') ) {
                $user = User::where('sso_user_id', $r->get('memberSSOid'))->first();
                if (isset($user) && $user->status == 'active' && is_object($user->get_active_membership())) {
                    return json_encode(array(
                        'code' => 1,
                        'isValid' => true
                    ), JSON_FORCE_OBJECT);
                }
                else {
                    // user not found, so he does not have a license
                    return json_encode(array(
                        'code' => 1,
                        'isValid' => false
                    ), JSON_FORCE_OBJECT);
                }
            }
            else {
                return json_encode(array(
                    'code' => 2,
                    'message' => "Invalid request."
                ), JSON_FORCE_OBJECT);
            }
        }
        else {
            return json_encode(array(
                'code' => 2,
                'message' => 'Permission denied.'
            ),JSON_FORCE_OBJECT);
        }
    }

    public function federation_list_of_licenses(Request $r)
    {
        if(RequestValidator::validate($r)){

        } else {

        }
    }

    public function federation_buy_license(Request $r)
    {
        if(RequestValidator::validate($r)){
            $token = IframePermission::createPermission($r->memberSSOid);
            return json_encode(array(
                'code' => 1,
                'iFrameUrl' => route('buy_license',[ 'token' => $token , 'sso_id' => $r->memberSSOid,'membership_id' => isset($r->membership_id) ? $r->membership_id : -1 ]).'?redirect_url='.$r->get('return_url')
            ),JSON_FORCE_OBJECT);
        } else {
            return json_encode(array(
                'code' => 2,
                'message' => 'Permission denied.'
            ),JSON_FORCE_OBJECT);
        }
    }

    public static function local_get_members_growth()
    {
        $active_memberships = UserMembership::where('status','active')->get();
        $data_paying = [];
        $data_non_paying = [];
        $all_members = [];
        $members_data = User::all();
        $members = [];
        foreach ($members_data as $member) {
            if ($member->hasRole('front-member')) {
                $members[$member->id] = $member;
            }
        }
        for ($i = 0 ;$i < 30;$i++) {
            $index = 30 - $i;
            $date = date('Y-m-d 0:0:0', strtotime("-" . (string)($index) . " days"));
            $memory = [];
            foreach ($active_memberships as $membership) {
                if ($membership->created_at < $date && !isset($memory[$membership->user_id])) {
                    if (isset($members[$membership->user_id])) {
                        $memory[$membership->user_id] = true;
                    }
                }
            }
            $potential_memberships = UserMembership::where([['created_at', '<', $date], ['updated_at', '>', $date], ['status', '<>', 'active']])->get();
            foreach ($potential_memberships as $membership) {
                if (!isset($memory[$membership->user_id]) && isset($members[$membership->user_id])) {
                    $memory[$membership->user_id] = true;
                }
            }
            $data_paying[$i + 1] = sizeof($memory);
            $non_paying_members = User::where('created_at','<',$date)->get();
            $non_p = 0;
            foreach ($non_paying_members as $member) {
                if (isset($members[$member->id])) {
                    $non_p++;
                }
            }
            $all_members[$i + 1] = $non_p;
            $data_non_paying[$i + 1] = $non_p - sizeof($memory);
        }

        return json_encode(array(
            'paying_members' => $data_paying,
            'non_paying_members' => $data_non_paying,
            'all_members' => $all_members
        ));
    }

    public function get_members_growth(Request $r)
    {
        if(RequestValidator::validate($r)){
            return self::local_get_members_growth($r);
        } else {
            return json_encode(array(
                'code' => 2,
                'message' => 'Permission denied.'
            ),JSON_FORCE_OBJECT);
        }

    }
}