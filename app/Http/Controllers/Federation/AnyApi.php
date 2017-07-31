<?php
namespace App\Http\Controllers\Federation;

class AnyApi {

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

    private static function convert_to_string($data){
        if(is_array($data)) {
            foreach ($data as $key=>$value) {
                if(is_array($value)){
                    $data[$key] = self::convert_to_string($value);
                } else {
                    $data[$key] = (string)$value;
                }
            }
            return $data;
        } else {
            return (string) $data;
        }
    }


    public static function send_curl($federation_url ,$data, $api_url, $method = 'GET')
    {
        $api_base = $federation_url;

//        $data['site_id'] = env('MY_API_ID',"");
//
//        if(is_array($data)) {
//            $data = self::convert_to_string($data);
//        }

        if ($method == 'GET')
        {
            $get_url = '';
            foreach ($data as $key => $e) {
                $get_url .= $key.'='.$e.'&';
            }
            $get_url = rtrim($get_url,'&');

            if ($get_url) {
                $api_url .= '?'. $get_url;
            }
            $ApiKey = self::generateApiKey($get_url);
        } else {
            $ApiKey = self::generateApiKey($data);
        }
        $curl = curl_init($api_base.'/'.$api_url);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($curl, CURLOPT_FRESH_CONNECT, TRUE);
        $headers = [
            'Content-Type: application/json',
            'ApiKey:'.$ApiKey,
            'Accept: application/json',
            'Cache-Control: no-cache'
        ];
        if (in_array($method, ['POST', 'PUT']) && is_array($data))
        {
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
        }
        curl_setopt($curl, CURLOPT_HTTPHEADER,$headers);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $curl_results = curl_exec($curl);
        $result = json_decode(str_replace('&quot;', '"', $curl_results));
        if (!empty(json_last_error()))
        {
            $result = str_replace('&quot;', '"', $curl_results);
        }
        curl_close($curl);
        return $result;
    }

}