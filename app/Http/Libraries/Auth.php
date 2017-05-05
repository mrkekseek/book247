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
        //ApiAuth::accounts_get_username('tk@div-art.com');
        //dd(ApiAuth::accounts_get(36));
        //dd(self::user());
        //ApiAuth::accounts_get_username('test5');
        //dd($session_sso);
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
        //ApiAuth::autorize($data);    
        //dd($data);        
        if (ApiAuth::autorize($data)['success'])
        {            
            //temp variable
            $sso_user_id = 36;
            $local_user = User::where('sso_user_id', $sso_user_id)->first();
            if (!empty($local_user))
            {
                Session::put('sso_user_id',$sso_user_id);            
                Cookie::queue(Cookie::forever('sso_user_id', $sso_user_id, '/', '.book247.da'));
                return true;
            }
            else
            {
                if (self::create_local_user($sso_user_id))
                {
                    Session::put('sso_user_id',$sso_user_id);            
                    Cookie::queue(Cookie::forever('sso_user_id', $sso_user_id, '/', '.book247.da'));
                    return true;
                }
            }
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
    
    private static function create_local_user($api_user_id)
    {
        $api_user = ApiAuth::accounts_get($api_user_id)['data'];                
        $new_local_user = [
            'sso_user_id'=>$api_user->id,
            'username'=>$api_user->username,
            //'password'=>'12345678',                    
            'user_type' =>6,
            'email'=>$api_user->email.'test',
            'first_name'=>$api_user->firstName,
            'last_name'=>$api_user->lastName,
            'middle_name'=>$api_user->middleName,
            'country_id'=>804,
            ];
            /*
            $validator = Validator::make($new_local_user, User::rules('POST'), User::$messages, User::$attributeNames);    
            dd($validator->getMessageBag()->toArray());
            if ($validator->fails()){
                //return $validator->errors()->all();
                return array(
                    'success'   => false,
                    'title'     => 'Error validating',
                    'errors'    => $validator->getMessageBag()->toArray()
                );
                }*/
        if (User::create($new_local_user))
        {
            Session::put('sso_user_id',$sso_user_id);            
            Cookie::queue(Cookie::forever('sso_user_id', $sso_user_id, '/', '.book247.da'));
            return true;
        }
        return false;
    }

}