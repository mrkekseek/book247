<?php
namespace App\Http\Controllers;


class Api {


    public static $error = '';

    private static function generateApiKey($data)
    {
        if (is_array($data) )
        {
            $data = json_encode($data,JSON_UNESCAPED_SLASHES);
        }
        $key = env('APIKEY','');
        if ($key) {
            $hash = base64_encode(hash_hmac('sha256', $data, $key, TRUE));
            return $hash;
        } else {
            return '';
        }

    }


    public static function send_curl($data, $api_url, $method = 'GET')
    {
        $api_base = env('APIURL','');
        $data[] = str_random(32);
        if ($method == 'GET')
        {
            $get_url = '';
            foreach ($data as $key => $e) {
                $get_url .= $key.'='.$e.'&';
            }
            if ($get_url) {
                $api_url .= '?'.$get_url;
            }
        }
        $ApiKey = self::generateApiKey($data);
        $curl = curl_init($api_base.'/'.$api_url);
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
