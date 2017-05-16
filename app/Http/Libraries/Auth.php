<?php

namespace App\Http\Libraries;

use App\Http\Libraries\ApiAuth;
use App\User;
use App\PersonalDetail;
use Illuminate\Support\Facades\Session;
use \Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Auth as AuthLocal; 

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
        if (AuthLocal::once(['username' => $data['email'], 'password' => $data['password'], 'sso_user_id' => NULL]))
        {   
            $local_id = AuthLocal::user()->id;
            $user = User::find($local_id)->toArray();                                  
            if (self::check_exist_api_user($user['username']))
            {   
                $sso_user = ApiAuth::accounts_get_by_username($user['username']);
                $sso_user_id = $sso_user['data']->id;
                $update_user = User::find($local_id);
                $update_user->update_from_api = true;
                $update_user->sso_user_id = $sso_user_id;
                $update_user->save();
                self::set_cookie_session($sso_user_id);
                return true;
            }
            else
            {                
                $new_sso_id = self::create_api_user($user, $data['password']);                                    
                if ($new_sso_id)
                {
                    $update_user = User::find($local_id);
                    $update_user->update_from_api = true;
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
            self::set_local_user($sso_user_id);
            return true;
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
    
    private static function set_cookie_session($sso_user_id)
    {   
        $domain = self::get_domain();        
        Session::put('sso_user_id',$sso_user_id);            
        Cookie::queue(Cookie::forever('sso_user_id', $sso_user_id, '/', $domain));            
    }

    private static function set_session()
    {
        $cookie_sso = Cookie::get('sso_user_id');                
        $session_sso = Session::get('sso_user_id');                
        if (!empty($cookie_sso) && !empty($session_sso) && $session_sso !== $cookie_sso)
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
                    'username'=>$api_user['data']->username,
                    'sso_user_id'=>NULL
                ])->first();
                if (!empty($exist))
                {                    
                    $exist->sso_user_id = $api_user['data']->id;
                    $exist->update_from_api = TRUE;
                    $exist->save();
                }
                Session::put('sso_user_id',$api_user['data']->id);
                self::set_local_user($api_user['data']->id);
            }
        }
        elseif (empty($cookie_sso) && !empty($session_sso))
        {
            Session::put('sso_user_id','');
        }
    }
    
    private static function set_local_user($sso_user_id)
    {
        $api_user = ApiAuth::accounts_get($sso_user_id)['data'];                
        $local_user = [
            'sso_user_id'=>$api_user->id,
            'username'=>$api_user->username,
            'email'=>$api_user->email,
            'first_name'=>$api_user->firstName,
            'last_name'=>$api_user->lastName,
            'middle_name'=>$api_user->middleName,            
            'country_id'=> config('constants.globalWebsite.defaultCountryId'),
            ];
        switch ($api_user->gender)
        {
            case (1): $local_user['gender'] = 'M'; break;
            case (2): $local_user['gender'] = 'F'; break;
        }
        $user = User::firstOrNew(['sso_user_id'=>$sso_user_id]);        
        $user->fill($local_user);        
        if (!$user->exists)
        {
            $user->save();
            $user->attachRole(6);
            self::set_personal_details($user->id, $api_user);
            self::set_cookie_session($sso_user_id);
            return true;
        }
        else
        {   
            if ($user->isDirty())
            {
                $user->update_from_api = true;
                $user->save();                                            
                self::set_personal_details($user->id, $api_user);
            } 
            self::set_cookie_session($sso_user_id);
            return true;
        }
        return false;
    }
    
    private static function set_personal_details($local_user_id, $api_user)
    {
        $personalDetail = PersonalDetail::firstOrNew(['user_id'=>$local_user_id]);
        $personalDetail->personal_email = $api_user->email;
        $personalDetail->date_of_birth = date('Y-m-d', strtotime($api_user->birthday));
        $personalDetail->save();
    }

    public static function create_api_user($user, $password = FALSE)
    {   
        if ($password)
        {
            $user['password_api'] = $password;
        }
        if (!self::check_exist_api_user($user['username']))
        {            
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
        $apiData['birthday'] = date('Y-m-d',strtotime($apiData['birthday'])).'T00:00:00';
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
    
    private static function check_exist_api_user($username)
    {        
        $exist = ApiAuth::checkExist($username);
        return $exist['success'];
    }
    
    private static function get_domain()
    {   
        $url = request()->server('HTTP_REFERER');
        $pieces = parse_url($url);
        $domain = isset($pieces['host']) ? $pieces['host'] : '';
        if (preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $domain, $regs)) {
          return '.'.$regs['domain'];
        }
        return false;
    }

}