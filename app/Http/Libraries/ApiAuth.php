<?php

namespace App\Http\Libraries;

class ApiAuth
{
    const APIKEY = 'apiKey-@f4g8-FH2-8809x-dj22aSwrL=cP24Zd234-TuJh87EqChVBGfs=SG564SD-fgAG47-747AhAP=U456=O97=Y=O6A=OC7b5645MNB-V4OO7Z-qw-OARSOc-SD456OFoCE-=64RW67=QOVq=';
    const APIURL = 'http://rankedinbookingsso-test.azurewebsites.net/';
    
    static $error  = '';

    public static function accounts_get($id = 0)
    { 
        $response = self::send_curl($id, 'api/Accounts/', 'GET');
        if ($response)
        {
            $result['success'] = true;
            $result['data'] = $response;
        }
        else
        {
            $result['success'] = false;
            $result['message'] = self::$error;
        }
        return $result;
    }
    
    public static function accounts_get_by_username($username)
    {
        $get= '?username='.$username;                
        $response = self::send_curl($get, 'api/Accounts/GetByUsername', 'GET');                
        if ($response)
        {
            $result['success'] = true;
            $result['data'] = $response;
        }
        else
        {
             $result['success'] = false;
             $result['message'] = self::$error;
        }
        return $result;
    }

    public static function accounts_update($data = [])
    {        
        $sortingArray = [
            'Id'=> 0,
            'Username'=> '',
            'Password'=> '',
            'Email'=> '',
            'Birthday'=> '',
            'Gender'=> 0,
            'CountryCode'=> '00',
            'FirstName'=> '',
            'MiddleName'=> '',
            'LastName'=> '',
            'ResetToken'=> '',
            'ResetTokenDate'=> null,
            'PhoneNumber' => ''

        ];
        $apiData = [                                    
            'ResetToken'=> '',
            'ResetTokenDate'=> '',            
            "CountryCode" => '00',            
        ];
        foreach ($data as $key=>$value)
        {
            switch ($key)
            {
                case 'id':
                    $apiData['Id'] = $value;
                    break;
                case 'username':
                    $apiData['Username'] = $value;
                    break;                
                case 'email':
                    $apiData['Email'] = $value;
                    break;
                case 'first_name':
                    $apiData['FirstName'] = $value;
                    break;
                case 'middle_name':
                    $apiData['MiddleName'] = $value;
                    break;
                case 'last_name':
                    $apiData['LastName'] = $value;
                    break;                
                case  ('gender'):
                    $apiData['Gender'] = $value;
                    break;
                case 'date_of_birth':                    
                    $apiData['Birthday'] = $value;
                    break;
                case 'mobile_number':
                    $apiData['PhoneNumber'] = $value;
                    break;   
            }
        }        
        foreach ($sortingArray as $key=>$value)
        {
            if (!empty($apiData[$key]))
            {
                $sortingArray[$key] = $apiData[$key];
            }
        }
        self::send_curl($sortingArray,'api/Accounts', 'PUT');                
        if (empty(self::$error))
        {
            $result['success'] = true;
        }
        else
        {
            $result['success'] = false;
            $result['message'] = self::$error;
        }
        return $result;        
    }
    
    public static function account_create($data = [])
    {
        $sortingArray = [
            'Id'=> 0,
            'Username'=> '',
            'Password'=> '',
            'Email'=> '',
            'Birthday'=> '',
            'Gender'=> 0,
            'CountryCode'=> '00',
            'FirstName'=> '',
            'MiddleName'=> '',
            'LastName'=> '',
            'ResetToken'=> '',
            'ResetTokenDate'=> null,
            'PhoneNumber' => ''
        ];
        $apiData = [
            'Id'=> 0,            
            'Birthday'=> date('Y-m-d').'T00:00:00',
            'Gender'=> 1,            
            'ResetToken'=> '',
            'ResetTokenDate'=> '',
            'Gender'=> 1,
            "CountryCode" => '00'
        ];
        foreach ($data as $key=>$value)
        {
            switch ($key)
            {
                case 'username':
                    $apiData['Username'] = $value;
                    break;
                case 'password_api':
                    $apiData['Password'] = $value;
                    break;
                case 'email':
                    $apiData['Email'] = $value;
                    break;
                case 'first_name':
                    $apiData['FirstName'] = $value;
                    break;
                case 'middle_name':
                    $apiData['MiddleName'] = $value;
                    break;
                case 'last_name':
                    $apiData['LastName'] = $value;
                    break;
                case 'mobile_number':
                    $apiData['PhoneNumber'] = $value;
                    break;                   
                case 'gender':
                    $apiData['Gender'] = $value;
                    break;                   
                case 'date_of_birth':
                    $apiData['Birthday'] = $value;
                    break;
            }
        }        
        foreach ($sortingArray as $key=>$value)
        {
            if (!empty($apiData[$key]))
            {
                $sortingArray[$key] = $apiData[$key];
            }
        }
        $response = self::send_curl($sortingArray,'api/Accounts', 'POST');
        if ($response)
        {
             $result['success'] = true;
             $result['data'] = $response;
        }
        else
        {
             $result['success'] = false;
             $result['message'] = self::$error;
        }
        return $result;
    }


    public static function checkExist($username)
    {        
        $get= '?username='.$username;
        $response = self::send_curl($get, 'api/Accounts/CheckIfExists', 'GET');
        if ($response)
        {
            $result['success'] = true;
        }
        else
        {
             $result['success'] = false;
             $result['message'] = self::$error;
        }
        return $result;
    }
    
    public static function autorize($data = [])
    {
        $apiData = [];        
        foreach ($data as $key=>$value)
        {
            switch ($key)
            {
                case 'email':
                    $apiData['Username'] = $value;
                    break;
                case 'password':
                    $apiData['Password'] = $value;
                    break;
            }
        }        
        $response = self::send_curl($apiData, 'api/Accounts/Authorize', 'POST');
        if ($response)
        {
            $result['success'] = true;
        }
        else
        {
             $result['success'] = false;
             $result['message'] = self::$error;
        }
        
        return $result;
    }
    
    public static function resetPassword($username)
    {
        $get= '?username='.$username;        
        $response = self::send_curl($get, 'api/Accounts/ResetPassword', 'GET');        
        if ($response)
        {
            $result['success'] = true;
            $result['data'] = $response;
        }
        else
        {
             $result['success'] = false;
             $result['message'] = self::$error;
        }
        return $result;
    }
    
    public static function updatePassword($data = [])
    {   
        self::send_curl($data, 'api/Accounts/PasswordUpdate', 'POST');
        if (empty(self::$error))
        {
            $result['success'] = true;
        }
        else
        {
            $result['success'] = false;
            $result['message'] = self::$error;
        }
        return $result;
    }

    private static function generateApiKey($data)
    {        
        if (is_array($data))
        {
            $data = json_encode($data,JSON_UNESCAPED_SLASHES);          
        }        
        $hash = base64_encode(hash_hmac('sha256', $data, self::APIKEY, TRUE));                        
        return $hash;
    }
    
    

    private static function send_curl($data, $api_url, $method = 'GET')
    {
        if ($method == 'GET')
        {
            $api_url.= (string)$data;
        }
        $ApiKey = self::generateApiKey($data);      
        $curl = curl_init(self::APIURL.$api_url);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);      
        $headers = [
          'Content-Type: application/json',        
          'ApiKey:'.$ApiKey,        
        ];
        if (in_array($method, ['POST', 'PUT']) && is_array($data))
        { 
          curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));                
          $headers[] = 'Accept: application/json';
        }
        else
        {
            $headers[] = 'Accept: text/plain';
        }
        curl_setopt($curl, CURLOPT_HTTPHEADER,$headers); 
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); 
        $curl_results = curl_exec($curl);
        $result = json_decode($curl_results);                  
        if (!empty(json_last_error()))
        {
            $result = $curl_results;
        }        
        curl_close($curl);        
        if ($result && !isset($result->Code))
        {   
            return $result;            
        }
        else
        {            
            self::$error = isset($result->Message)?$result->Message:'';          
            return false;
        }
    }
}