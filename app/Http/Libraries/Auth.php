<?php

namespace App\Http\Libraries;

use App\Http\Libraries\ApiAuth;
//use Illuminate\Foundation\Auth\User as Authenticatable;
use App\User;
use Illuminate\Support\Facades\Session;
use \Illuminate\Support\Facades\Cookie;

class Auth
{
    public static $error = '';
    
    public function __construct() 
    {
        $cookie_sso = Cookie::get('sso_user_id');
        //dd($cookie_sso);
        $session_sso = Session::get('sso_user_id');
        if (!empty($cookie_sso) && empty($session_sso))
        {
            $user_id = decrypt($user_id);
            $user = ApiAuth::accounts_get($user_id);
            if ($user['success'])
            {
                Session::put('sso_user_id',$user['data']->id);
            }
        }
    }
    public static function user()
    {        
        //dd(Session::all());                
        //dd(Cookie::get('sso_user_id'));          
        $session_sso = Session::get('sso_user_id');        
        if (!empty($session_sso))
        {   
            $user_locale = User::where('sso_user_id',$session_sso)->first();
            //dd($user_locale);
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
        //dd(ApiAuth::autorize($data));
        //dd(ApiAuth::accounts_get(33));
        //dd(ApiAuth::checkExist('tk3@div-art.com'));
        if (ApiAuth::autorize($data)['success'])
        {            
            $sso_user_id =12;
            Session::put('sso_user_id',$sso_user_id);            
            Cookie::queue(Cookie::forever('sso_user_id', $sso_user_id, '/', '.book247.da'));
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
        Cookie::queue(Cookie::forget('sso_user_id', '/', '.book247.da')); 
    }

}