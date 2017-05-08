<?php

namespace App\Http\Libraries;

use App\Http\Libraries\ApiAuth;
use App\User;
use Illuminate\Support\Facades\Session;
use \Illuminate\Support\Facades\Cookie;

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
            self::check_exist_local_user($session_sso);
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
        if (ApiAuth::autorize($data)['success'])
        {  
            $sso_user = ApiAuth::accounts_get_by_username($data['email']);            
            $sso_user_id = $sso_user['data']->id;
            self::check_exist_local_user($sso_user_id);
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
        if (!empty($cookie_sso) && empty($session_sso))
        {
            $sso_user_id = $cookie_sso;
            $user = ApiAuth::accounts_get($sso_user_id);
            if ($user['success'])
            {
                Session::put('sso_user_id',$user['data']->id);
            }
        }
        elseif (empty($cookie_sso) && !empty($session_sso))
        {
            Session::put('sso_user_id','');
        }
    }
    
    private static function check_exist_local_user($sso_user_id)
    {        
        $local_user = User::where('sso_user_id', $sso_user_id)->first();
        if (self::set_local_user($sso_user_id))
        {
            self::set_cookie_session($sso_user_id);
            return true;
        }
        return false;
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
            'country_id'=>804,
            ];        
        $user = User::firstOrNew(['sso_user_id'=>$sso_user_id]);
        $user->fill($local_user);
        if (!$user->exists)
        {
            $user->save();
            $user->attachRole(6);            
            return true;
        }
        else
        {
            $user->save();
            return true;
        }
        return false;
    }
    
    private static function get_domain()
    {   
        $url = $_SERVER['HTTP_REFERER'];        
        $pieces = parse_url($url);
        $domain = isset($pieces['host']) ? $pieces['host'] : '';
        if (preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $domain, $regs)) {
          return '.'.$regs['domain'];
        }
        return false;
    }

}