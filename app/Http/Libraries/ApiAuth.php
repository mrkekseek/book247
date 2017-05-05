<?php

namespace App\Http\Libraries;

class ApiAuth
{
    const APIKEY = 'apiKey-@f4g8-FH2-8809x-dj22aSwrL=cP24Zd234-TuJh87EqChVBGfs=SG564SD-fgAG47-747AhAP=U456=O97=Y=O6A=OC7b5645MNB-V4OO7Z-qw-OARSOc-SD456OFoCE-=64RW67=QOVq=';
    const APIURL = 'http://rankedinbookingsso.azurewebsites.net/';
    
    static $error  = '';
    
    
    public function info()
    {
      //$password = bcrypt('test'.'Salt@1fj38jGTlkmjkx9845GF9J4'.'test');
      $array = [
        'Id'=> 7,
        'Username'=> 'test2',
        'Password'=> 'test',
        'Email'=> 'tk@div-art.com',
        'Birthday'=> '2017-05-12T12:55:20.337',
        'Gender'=> 1,
        'CountryCode'=> '33',
        'FirstName'=> null,
        'MiddleName'=> null,
        'LastName'=> null,
        'ResetToken'=> null,
        'ResetTokenDate'=> null
        ];
        $array2 = [
            'Id'=> 0,
            'Username'=> 'test2',
            'Password'=> 'test',
            'Email'=> 'tk@div-art.com',
            'Birthday'=> '2017-04-12T12:55:20.337',
            'Gender'=> 1,
            'CountryCode'=> '33',
            'FirstName'=> 'testfirstname',
            'MiddleName'=> 'testmidlename',
            'LastName'=> 'testlastname',
            'ResetToken'=> 'string',
            'ResetTokenDate'=> null
            ];
        $array3 = [
            'username'=>'test2',
            'password'=>'test',
        ];
        $str = '{"Id":8,"Username":"test2","Password":"test","Email":"tk@div-art.com","Birthday":"2017-04-12T12:55:20.337","Gender":1,"CountryCode":"33","FirstName":"testfirstname","MiddleName":"testmidlename","LastName":"testlastname","ResetToken":"string","ResetTokenDate":null}';
        $array4 = [
            'Id'=> 8,
            'Username'=> 'test2',
            'Password'=> 'test',
            'Email'=> 'tk@div-art.com',
            'Birthday'=> '2017-04-12T12:55:20.337',
            'Gender'=> 1,
            'CountryCode'=> '33',
            'FirstName'=> 'testfirstname',
            'MiddleName'=> 'testmidlename',
            'LastName'=> 'testlastname',
            'ResetToken'=> 'string',
            'ResetTokenDate'=> null,
            ];
        echo '<br>';
        //echo base64_encode(hash_hmac('sha256', json_encode($array3), 'apiKey-@f4g8-FH2-8809x-dj22aSwrL=cP24Zd234-TuJh87EqChVBGfs=SG564SD-fgAG47-747AhAP=U456=O97=Y=O6A=OC7b5645MNB-V4OO7Z-qw-OARSOc-SD456OFoCE-=64RW67=QOVq=', TRUE));
        echo base64_encode(hash_hmac('sha256', $str, 'apiKey-@f4g8-FH2-8809x-dj22aSwrL=cP24Zd234-TuJh87EqChVBGfs=SG564SD-fgAG47-747AhAP=U456=O97=Y=O6A=OC7b5645MNB-V4OO7Z-qw-OARSOc-SD456OFoCE-=64RW67=QOVq=', TRUE));
        echo '<br>';
    }

    public static function accounts_get($id = 7)
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
    
    public static function accounts_get_username($username)
    {
        //$get= '?username='.$username;
        $get= $username;
        $response = self::send_curl($get, 'api/Accounts/', 'GET');        
        //dd($response);
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

    public static function accounts_update($data = [])
    {
        //dd($data);
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
            'ResetTokenDate'=> null
        ];
        $apiData = [            
            //'Birthday'=> '1986-09-03T01:01:01.337',            
            'ResetToken'=> '',
            'ResetTokenDate'=> '',            
            "CountryCode" => '00',            
        ];
        foreach ($data as $key=>$value)
        {
            switch ($key)
            {
                case 'sso_user_id':
                    $apiData['Id'] = $value;
                    break;
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
                case  ($key == 'gender' && $value == 'F'):
                    $apiData['Gender'] = 2;
                    break;
                case  ($key == 'gender' && $value == 'M'):                    
                    $apiData['Gender'] = 1;
                    break;
                case 'birthday':
                    //dd();
                    $apiData['Birthday'] = date('Y-m-d',strtotime($value)).'T00:00:00';
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
        //dd($sortingArray);
        //dd(self::accounts_get(36));
        self::send_curl($sortingArray,'api/Accounts', 'PUT');        
        //dd(self::$error);
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
            'ResetTokenDate'=> null
        ];
        $apiData = [
            'Id'=> 0,
            'Birthday'=> '1986-09-03T01:01:01.337',
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
        /*
        $data = [
            'Username'=>'test2',
            'Password'=>'test',
        ];*/
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
        //dd($apiData);
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
        /*
        $data = [
            "Credentials" => [
              "Username" => "test5",
              "Password" => ""
            ],
            "Token"=> 'ra2FR+MkNPCxpJL9m86drw==',
            "NewPassword"=> "string",
        ];
        */
        //dd(csrf_token());
        //dd($data);
        $response = self::send_curl($data, 'api/Accounts/PasswordUpdate', 'POST');                
        //dd(self::$error);
        dd($response);
        if ($response)
        {
            $result['success'] = true;            
        }
        else
        {
             $result['success'] = false;
             $result['message'] = self::$error;
        }
        //dd($result);
        return $result;
    }

    private static function generateApiKey($data)
    {
        if (is_array($data))
        {
          $data = json_encode($data);          
        }        
        $hash = base64_encode(hash_hmac('sha256', $data, self::APIKEY, TRUE));
        //dd($hash);
        //dd(base64_encode(hash_hmac('sha256', '?username=test5', self::APIKEY, TRUE)));
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
        //dd($curl_results);
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