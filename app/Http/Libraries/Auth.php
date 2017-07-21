<?php

namespace App\Http\Libraries;

use App\Http\Libraries\ApiAuth;
use App\User;
use App\PersonalDetail;
use App\OptimizeSearchMembers;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use \Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Auth as AuthLocal;
use App\Http\Controllers\AppSettings;
use Webpatser\Countries\Countries;

class Auth
{
    public static $error = '';    
    
    public function __construct() 
    {
        
    }
    public static function user()
    {   
        self::set_session();
        $session_sso = Session::get('sso_user_id');                       
        if (!empty($session_sso))
        {   
            $user_locale = User::where('sso_user_id',$session_sso)->first();            
            if ($user_locale)
            {
                return $user_locale;
            }
        }        
        return new User();
    }

    public static function check()
    {        
        $user_session = Session::get('sso_user_id');
        return (!empty($user_session));
    }
    
    public static function guest()
    {        
        $user_session = Session::get('sso_user_id');
        return (!empty($user_session));
    }

    public static function attempt($data = [])
    {
        if (AuthLocal::once(['username' => $data['email'], 'password' => $data['password'], 'sso_user_id' => NULL]) || AuthLocal::once(['email' => $data['email'], 'password' => $data['password'], 'sso_user_id' => NULL]))
        {   
            $local_id = AuthLocal::user()->id;
            $user = User::find($local_id)->toArray();                                  
            if (self::check_exist_api_user($user['username']))
            {   
                $sso_user = ApiAuth::accounts_get_by_username($user['username']);
                $sso_user_id = $sso_user['data']->id;
                $update_user = User::find($local_id);
                $update_user->sso_user_id = $sso_user_id;
                $update_user->save();
                self::set_cookie_session($sso_user_id);
                return true;
            }
            else
            {                
                $personalDetail = PersonalDetail::where('user_id', $local_id)->first()->toArray();
                $dataApiUser = $user + $personalDetail;
                $new_sso_id = self::create_api_user($dataApiUser, $data['password']);                                    
                if ($new_sso_id)
                {
                    $update_user = User::find($local_id);
                    $update_user->sso_user_id = $new_sso_id;
                    $update_user->save();
                    self::set_cookie_session($new_sso_id);
                    return true;
                }
            }
        }        

        if (ApiAuth::autorize($data)['success'])
        {
            $sso_user = ApiAuth::accounts_get_by_username($data['email']);            
            $sso_user_id = $sso_user['data']->id;
            $exist = User::where('sso_user_id',$sso_user_id)->first();
            if ( ! empty($exist))
            {
                self::set_local_user($sso_user_id);
                return true;
            }
            return false;
        }
        else
        {            
            self::$error .= ApiAuth::autorize($data)['message'];
            return false;        
        }
    }
    
    public static function logout()
    {        
        $domain = self::get_domain();        
        Session::put('sso_user_id','');
        Cookie::queue(Cookie::forget('sso_user_id', '/', $domain)); 
    }
    
    public static function set_cookie_session($sso_user_id)
    {   
        $domain = self::get_domain();
        Session::flash('new_auth', TRUE);            
        Session::put('sso_user_id',$sso_user_id);            
        Cookie::queue(Cookie::forever('sso_user_id', $sso_user_id, '/', $domain));
    }

    public static function loginUsingId($id)
    {
        $user = User::find($id);
        if ($user) {
            if (self::check_exist_api_user($user['username'])) {
                $sso_user = ApiAuth::accounts_get_by_username($user['email']);
                if (isset($sso_user['data'])) {
                    self::set_cookie_session($sso_user['data']->id);
                    return true;
                }
                return false;
            }
        }
        return false;
    }

    private static function set_session()
    {
        $cookie_sso = Cookie::get('sso_user_id');                
        $session_sso = Session::get('sso_user_id');
        $new_auth = Session::get('new_auth');
        if (!empty($cookie_sso) && !empty($session_sso) && $session_sso !== $cookie_sso && empty($new_auth))
        {            
            $session_sso = false;
        }
        if (!empty($cookie_sso) && empty($session_sso))
        {
            $sso_user_id = $cookie_sso;
            $api_user = ApiAuth::accounts_get($sso_user_id);
            if ($api_user['success'])
            {
                $exist = User::where([
                    'sso_user_id'=>$api_user['data']->id,
                ])->first();
                if ( ! empty($exist))
                {
                    Session::put('sso_user_id',$api_user['data']->id);
                    self::set_local_user($api_user['data']->id);
                }
            }
        }
        elseif (empty($cookie_sso) && !empty($session_sso))
        {
            Session::put('sso_user_id','');
        }
    }
    
    public static function set_local_user($sso_user_id)
    {
        $api_user = ApiAuth::accounts_get($sso_user_id)['data'];
        $local_user = [
            'sso_user_id'=>$api_user->id,
            'username'=>$api_user->username,
            'email'=>$api_user->email,
            'first_name'=>$api_user->firstName,
            'last_name'=>$api_user->lastName,
            'middle_name'=>$api_user->middleName,            
            ];
        switch ($api_user->gender)
        {
            case (1): $local_user['gender'] = 'M'; break;
            case (2): $local_user['gender'] = 'F'; break;
        }
        
        $user = User::firstOrNew(['username'=>$api_user->username]);
        $user = ( ! $user->exists) ? User::firstOrNew(['email'=>$api_user->username]): $user;
        $country = Countries::find( $user->country_id);
        if (!$country) {
            $user->country_id = AppSettings::get_setting_value_by_name('globalWebsite_defaultCountryId');
        }
        $local_user['country_id'] = ! $user->exists ? AppSettings::get_setting_value_by_name('globalWebsite_defaultCountryId') : $user->country_id;
        $user->fill($local_user);        
        if (!$user->exists)
        {
            $user->save();
            $user->attachRole(6);
            self::set_personal_details($user->id, $api_user);
            self::set_cookie_session($sso_user_id);
            $searchMembers = new OptimizeSearchMembers();
            $searchMembers->add_missing_members([$user->id]);
            return true;
        }
        else
        {   
            if ($user->isDirty())
            {
                $user->save();                                            
            } 
            self::set_personal_details($user->id, $api_user);
            self::set_cookie_session($sso_user_id);
            return true;
        }
        return false;
    }
    
    public static function set_personal_details($local_user_id, $api_user)
    {
        $personalDetail = PersonalDetail::firstOrNew(['user_id'=>$local_user_id]);
        $personalDetail->personal_email = $api_user->email;
        /*if (empty($api_user->phoneNumber) && empty($personalDetail->mobile_number) )
        {
            $personalDetail->mobile_number = rand(100000, 999999).rand(100000, 999999).rand(100000, 999999);
        }
        else
        {
            $personalDetail->mobile_number = $api_user->phoneNumber;
        }*/

        if (!empty($api_user->phoneNumber)){
            $personalDetail->mobile_number = $api_user->phoneNumber;
        }
        elseif (!empty($personalDetail->mobile_number)){
            //$personalDetail->mobile_number = $api_user->phon;
        }
        else{
            $personalDetail->mobile_number = Carbon::now()->timestamp . rand(1000, 9999).rand(1000, 9999);
        }

        $personalDetail->date_of_birth = date('Y-m-d', strtotime($api_user->birthday));
        $personalDetail->save();
    }
    
    public static function create_local_user($sso_user_id = FALSE, $sso_username = FALSE, $role = 6)
    {
        $api_user = ! empty($sso_username) ? ApiAuth::accounts_get_by_username($sso_username)['data'] : ApiAuth::accounts_get($sso_user_id)['data'];
        $local_user = [
            'sso_user_id'=>$api_user->id,
            'username'=>$api_user->username,
            'email'=>$api_user->email,
            'first_name'=>$api_user->firstName,
            'last_name'=>$api_user->lastName,
            'middle_name'=>$api_user->middleName,            
            'country_id'=>AppSettings::get_setting_value_by_name('globalWebsite_defaultCountryId')
            ];
        switch ($api_user->gender)
        {
            case (1): $local_user['gender'] = 'M'; break;
            case (2): $local_user['gender'] = 'F'; break;
        }
        $user = new User();
        $user->fill($local_user);
        if ($user->save())
        {
            $user->attachRole($role);
            self::set_personal_details($user->id, $api_user);
            $searchMembers = new OptimizeSearchMembers();
            $searchMembers->add_missing_members([$user->id]);
            return $user;
        }
        return FALSE;
    }

    public static function create_api_user($user, $password = FALSE)
    {
        if ($password)
        {
            $user['password_api'] = $password;
        }
        if (!self::check_exist_api_user($user['username']))
        {
            if (isset($user['gender']))
            {
                switch ($user['gender'])
                {
                    case ('M'): $user['gender'] = 1; break;
                    case ('F'): $user['gender'] = 2; break;
                }
            }
            else
            {
                $user['gender'] = 1;
            }
            if (isset($user['date_of_birth']))
            {
                $user['date_of_birth'] = date('Y-m-d', strtotime($user['date_of_birth'])).'T00:00:00';
            }
            $api_user = ApiAuth::account_create($user);                    
            if ($api_user['success'])
            {
                return $api_user['data'];                
            }
            else
            {
                self::$error = $api_user['message'];
                return false;
            }         
        }
        else
        {
            self::$error = 'Current user already registered';
            return false;
        }
    }
    
    public static function update_api_user($user)
    {
        $apiData = $user;
        $apiData['id'] = (int) $apiData['sso_user_id'];
        unset($apiData['sso_user_id']);
        switch ($apiData['gender'])
        {
            case ('M'): $apiData['gender'] = 1; break;
            case ('F'): $apiData['gender'] = 2; break;
        }
        $apiData['date_of_birth'] = isset($apiData['date_of_birth']) ? $apiData['date_of_birth'] : '';
        $apiData['date_of_birth'] = date('Y-m-d',strtotime($apiData['date_of_birth'])).'T00:00:00';
        $api_user = ApiAuth::accounts_update($apiData);
        if ($api_user['success'])
        {
            return true;
        }
        else
        {
            self::$error = $api_user['message'];
            return false;            
        }        
    }
    
    public static function check_exist_api_user($username)
    {
        $exist = ApiAuth::checkExist($username);
        return $exist['success'];
    }
    
    private static function get_domain()
    {   
        $host = $_SERVER['HTTP_HOST'];
        if (preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $host, $regs)) {          
          return '.'.$regs['domain'];
        }
        return FALSE;
    }

}