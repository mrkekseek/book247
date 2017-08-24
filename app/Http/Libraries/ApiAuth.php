<?php

namespace App\Http\Libraries;

use App\PersonalDetail;
use App\User;
use App\Http\Libraries\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Validator;
use Webpatser\Countries\Countries;
use App\Http\Controllers\AppSettings;
use App\Role;
use App;
use Exception;

class ApiAuth
{
    static $error = '';

    public static function accounts_get($id = 0)
    {
        $response = self::send_curl($id, 'api/Accounts/', 'GET');
        if ($response) {
            $result['success'] = true;
            $result['data'] = $response;
        } else {
            $result['success'] = false;
            $result['message'] = self::$error;
        }
        return $result;
    }

    public static function accounts_get_by_username($username)
    {
        $get = '?username=' . $username;
        $response = self::send_curl($get, 'api/Accounts/GetByUsername', 'GET');
        if ($response) {
            $result['success'] = true;
            $result['data'] = $response;
        } else {
            $result['success'] = false;
            $result['message'] = self::$error;
        }
        return $result;
    }

    public static function validate_user_for_email_change($sso_user_id, $email) {
        $sso_user = self::accounts_get_by_username($email);
        if (!isset($sso_user['data']->id) || $sso_user['data']->id == $sso_user_id ) {
            return true;
        }
        return false;
    }

    public static function accounts_update($data = [])
    {
        $sortingArray = [
            'Id' => 0,
            'Username' => '',
            'Password' => '',
            'Email' => '',
            'Birthday' => '',
            'Gender' => 0,
            'CountryCode' => '00',
            'FirstName' => '',
            'MiddleName' => '',
            'LastName' => '',
            'ResetToken' => '',
            'ResetTokenDate' => null,
            'PhoneNumber' => ''

        ];
        $apiData = [
            'ResetToken' => '',
            'ResetTokenDate' => '',
            "CountryCode" => '00',
        ];
        foreach ($data as $key => $value) {
            switch ($key) {
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
                case 'country_iso_3166_2':
                    $apiData['CountryCode'] = $value;
                    break;
            }
        }
        foreach ($sortingArray as $key => $value) {
            if (!empty($apiData[$key])) {
                $sortingArray[$key] = $apiData[$key];
            }
        }

        if (self::validate_user_for_email_change($apiData['Id'],$apiData['Email'])) {
            self::send_curl($sortingArray, 'api/Accounts', 'PUT');
            if (empty(self::$error)) {
                $result['success'] = true;
            } else {
                $result['success'] = false;
                $result['message'] = self::$error;
            }
        } else {
            $result['success'] = false;
            $result['message'] = 'There is an user with same email on the single sign on.';
        }
        return $result;
    }

    public static function account_create($data = [])
    {
        $sortingArray = [
            'Id' => 0,
            'Username' => '',
            'Password' => '',
            'Email' => '',
            'Birthday' => '',
            'Gender' => 0,
            'CountryCode' => '00',
            'FirstName' => '',
            'MiddleName' => '',
            'LastName' => '',
            'ResetToken' => '',
            'ResetTokenDate' => null,
            'PhoneNumber' => ''
        ];
        $apiData = [
            'Id' => 0,
            'Birthday' => date('Y-m-d') . 'T00:00:00',
            'Gender' => 1,
            'ResetToken' => '',
            'ResetTokenDate' => '',
            'Gender' => 1,
            "CountryCode" => '00'
        ];
        foreach ($data as $key => $value) {
            switch ($key) {
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
        foreach ($sortingArray as $key => $value) {
            if (!empty($apiData[$key])) {
                $sortingArray[$key] = $apiData[$key];
            }
        }

        if (self::checkExist($apiData['Username'])) {
            $result['success'] = false;
            $result['message'] = 'User already registered in the single sign on.';
        }

        $response = self::send_curl($sortingArray, 'api/Accounts', 'POST');
        if ($response) {
            $result['success'] = true;
            $result['data'] = $response;
        } else {
            $result['success'] = false;
            $result['message'] = self::$error;
        }
        return $result;
    }

    public static function checkExist($username)
    {
        $get = '?username=' . $username;
        $response = self::send_curl($get, 'api/Accounts/CheckIfExists', 'GET');
        if ($response) {
            $result['success'] = true;
        } else {
            $result['success'] = false;
            $result['message'] = self::$error;
        }
        return $result;
    }

    public static function authorize($data = [])
    {
        $apiData = [];
        foreach ($data as $key => $value) {
            switch ($key) {
                case 'username':
                    $apiData['Username'] = $value;
                    break;
                case 'password':
                    $apiData['Password'] = $value;
                    break;
            }
        }
        $response = self::send_curl($apiData, 'api/Accounts/Authorize', 'POST');
        if ($response) {
            $result['success'] = true;
        } else {
            $result['success'] = false;
            $result['message'] = self::$error;
        }

        return $result;
    }

    public static function resetPassword($username)
    {
        $get = '?username=' . $username;
        $response = self::send_curl($get, 'api/Accounts/ResetPassword', 'GET');
        if ($response) {
            $result['success'] = true;
            $result['data'] = $response;
        } else {
            $result['success'] = false;
            $result['message'] = self::$error;
        }
        return $result;
    }

    public static function updatePassword($data = [])
    {
        self::send_curl($data, 'api/Accounts/PasswordUpdate', 'POST');
        if (empty(self::$error)) {
            $result['success'] = true;
        } else {
            $result['success'] = false;
            $result['message'] = self::$error;
        }
        return $result;
    }
    
    public static function account_get_by_phone_number($phone_number)
    {
        $get = '?phoneNumber='.$phone_number;
        $response = self::send_curl($get, 'api/Accounts/GetByPhoneNumber', 'GET');
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

    private static function generateApiKey($data)
    {
        if (is_array($data)) {
            $data = json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        }
        $hash = base64_encode(hash_hmac('sha256', $data, env('SSO_API_APIKEY',false), TRUE));
        return $hash;
    }

    private static function send_curl($data, $api_url, $method = 'GET')
    {
        $toLog['title'] = 'ApiAuth::send_curl - '.$api_url;

        if ($method == 'GET') {
            $api_url .= (string)$data;
        }
        $ApiKey = self::generateApiKey($data);

        $toLog['api_url'] = $api_url;
        $toLog['data'] = $data;
        $toLog['api_key'] = $ApiKey;

        try {
            $curl = curl_init(env('SSO_API',false) . $api_url);

            if (FALSE === $curl)
                throw new Exception('failed to initialize');

            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
            $headers = [
                'Content-Type: application/json',
                'ApiKey:' . $ApiKey,
            ];

            if (in_array($method, ['POST', 'PUT']) && is_array($data)) {
                curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
                $headers[] = 'Accept: application/json';
            } else {
                $headers[] = 'Accept: text/plain';
            }

            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

            if (App::environment('local') || env('VerifyCert',false)){
                curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
            }

            $curl_results = curl_exec($curl);

            if (FALSE === $curl_results)
                throw new Exception(curl_error($curl), curl_errno($curl));

        } catch(Exception $e) {

            trigger_error(sprintf(
                'Curl failed with error #%d: %s',
                $e->getCode(), $e->getMessage()),
                E_USER_ERROR);
        }

        $result = json_decode($curl_results);
        $toLog['curl_result'] = $curl_results;

        if (!empty(json_last_error())) {
            $result = $curl_results;
        }
        curl_close($curl);
        self::log_actions($toLog);

        if ($result && !isset($result->Code)) {
            return $result;
        } else {
            self::$error = isset($result->Message) ? $result->Message : '';
            return false;
        }
    }

    public static function generateKey($data)
    {
        if (is_array($data)) {
            $data = json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        }
        $hash = base64_encode(hash_hmac('sha256', $data, env('SSO_API_APIKEY',false), TRUE));
        return $hash;
    }

    public static function send($data, $api_url, $method = 'GET')
    {
        $toLog['title'] = 'ApiAuth::send - '.$api_url;

        if ($method == 'GET') {
            $api_url .= (string)$data;
        }
        $ApiKey = self::generateApiKey($data);

        $toLog['api_url'] = $api_url;
        $toLog['data'] = $data;
        $toLog['api_key'] = $ApiKey;

        $curl = curl_init(env('SSO_API',false) . $api_url);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        $headers = [
            'Content-Type: application/json',
            'ApiKey:' . $ApiKey,
        ];

        if (in_array($method, ['POST', 'PUT']) && is_array($data)) {
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
            $headers[] = 'Accept: application/json';
        }
        else {
            $headers[] = 'Accept: text/plain';
        }

        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        if (App::environment('local')){
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        }

        $curl_results = curl_exec($curl);
        $toLog['curl_result'] = $curl_results;

        $result = json_decode($curl_results);
        if (!empty(json_last_error())) {
            $result = $curl_results;
        }

        curl_close($curl);
        self::log_actions($toLog);

        if ($result && !isset($result->Code)) {
            return $result;
        } else {
            self::$error = isset($result->Message) ? $result->Message : '';
            return false;
        }
    }

    public static function synchronize($sso_id)
    {
        $user = User::where('sso_user_id', $sso_id)->first();
        if (isset($user)) {
            return true;
        }
        $account = self::accounts_get($sso_id);
        if ($account['success'] == false) {
            return "User not found in SSO!";
        }
        $sso_user = $account['data'];
        $similar_user = User::where('username',$sso_user->username)->first();
        if (isset($similar_user)) {
            $similar_user->sso_user_id = $sso_id;
            if ($similar_user->save()){
                return true;
            }
            return "User found locally! Cannot save this SSO!";
        } else {
            $country = Countries::where('iso_3166_2',$sso_user->countryCode)->first();
            $userType = Role::where('name','=','front-user')->first();

            $credentials = [
                'sso_user_id' => $sso_user->id,
                'first_name' => $sso_user->firstName,
                'middle_name' => $sso_user->middleName,
                'last_name' => $sso_user->lastName,
                'username' => $sso_user->username,
                'email' => $sso_user->email,
                'country_id' => isset($country) ? $country->id : AppSettings::get_setting_value_by_name('globalWebsite_defaultCountryId') ,
                'status' => 'active',
                'gender' => $sso_user->gender == 2 ? 'F' : 'M',
                'user_type' => @$userType->id,
                'password'  => '1122334455'
            ];

            $validator = Validator::make($credentials, User::rules('POST'), User::$messages, User::$attributeNames);

            if ($validator->fails()){
                //return $validator->errors()->all();
                return array(
                    'success'   => false,
                    'title'     => 'Error validating',
                    'errors'    => $validator->getMessageBag()->toArray()
                );
            }
            $user = User::create($credentials);
            $user->attachRole($userType);

            $user = User::where('sso_user_id',$sso_id)->first();
            $user_info = new PersonalDetail();
            $i = 0;
            $number = "";
            while ($i<18) {
                $number .= rand(0,9);
                $i += 1;
            }
            $user_info->fill([
                'user_id' => $user->id,
                'personal_email' => $user->email,
                'mobile_number' => $sso_user->phoneNumber ? $sso_user->phoneNumber : $number,
                'date_of_birth' => $sso_user->birthday
            ]);
            if (!$user_info->save()){
                return "User info cannot be saved locally!";
            }

            return true;
        }

    }

    public static function getActivities(){
        $get = '';
        $response = self::send_curl($get, 'api/Enums/GetActivities', 'GET');
        if ($response) {
            $result['success'] = true;
            $result['activities'] = $response;
        } else {
            $result['success'] = false;
            $result['message'] = self::$error;
        }
        return $result;
    }

    public static function log_actions($info){
        Log::useDailyFiles(storage_path().'/logs/apiAuth.log');

        $textToWrite = 'New API call' .PHP_EOL;
        foreach($info as $key=>$val){
            $textToWrite.= $key . ' - ' . stripslashes(json_encode($val)).PHP_EOL;
        }

        $textToWrite.=PHP_EOL;
        Log::info($textToWrite);
    }
}