<?php

namespace App\Http\Libraries;

use App\Http\Libraries\ApiAuth;
use App\User;
use Validator;
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
        //dd($_SERVER);        
        //$domain = parse_url($_SERVER['HTTP_HOST']);
        //dd(parse_url($domain));
        //dd($domain);
        
        
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
        //self::set_session();
        $user_session = Session::get('sso_user_id');
        return (!empty($user_session));
    }
    
    public static function guest()
    {
        //self::set_session();
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
        Session::put('sso_user_id','');
        Cookie::queue(Cookie::forget('sso_user_id', '/', '.'.$_SERVER['HTTP_HOST'])); 
    }
    
    private static function set_cookie_session($sso_user_id)
    {
        
        Session::put('sso_user_id',$sso_user_id);            
        Cookie::queue(Cookie::forever('sso_user_id', $sso_user_id, '/', '.'.$_SERVER['HTTP_HOST']));            
    }

    private static function set_session()
    {
        $cookie_sso = Cookie::get('sso_user_id');                
        $session_sso = Session::get('sso_user_id');
        
        //dd($cookie_sso);
        //dd($session_sso);
        if (!empty($cookie_sso) && empty($session_sso))
        {
            $sso_user_id = $cookie_sso;
            $user = ApiAuth::accounts_get($sso_user_id);
            if ($user['success'])
            {
                Session::put('sso_user_id',$user['data']->id);
            }
        }
        elseif (empty($cookie_sso) && empty($session_sso))
        {
            Session::put('sso_user_id','');
        }
    }
    
    private static function check_exist_local_user($sso_user_id)
    {        
        $local_user = User::where('sso_user_id', $sso_user_id)->first();
        if (!empty($local_user))
        {
            self::set_cookie_session($sso_user_id);
        }
        else
        {
            if (self::create_local_user($sso_user_id))
            {
                self::set_cookie_session($sso_user_id);
                return true;
            }
        }
    }    
    
    private static function create_local_user($sso_user_id)
    {
        $api_user = ApiAuth::accounts_get($sso_user_id)['data'];                
        $new_local_user = [
            'sso_user_id'=>$api_user->id,
            'username'=>$api_user->username,            
            'user_type' =>6,
            'email'=>$api_user->email,
            'first_name'=>$api_user->firstName,
            'last_name'=>$api_user->lastName,
            'middle_name'=>$api_user->middleName,
            'country_id'=>804,
            ];                    
        try{
            $user = User::create($new_local_user);
            $user->attachRole(6);            
            self::set_cookie_session($sso_user_id);
            return true;            
        }
            catch (\Exception $ex){
            return false;                    
        }
    }

}