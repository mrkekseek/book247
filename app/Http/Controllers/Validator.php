<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Validator
{
    public static function validate(Request $r)
    {
        $key = env('APIKEY');
        if ($key) {
            if ($r->method() == "GET") {
                $get_url = '';
                foreach ($r->all() as $key => $e) {
                    $get_url .= $key.'='.$e.'&';
                }
                $get_url = rtrim($get_url,'&');
                $hash = self::generateApiKey($get_url);
//                return $get_url. '  ' .$hash. '  ' .$r->header('apiKey');
            } else {
                $hash = self::generateApiKey($r->all());
            }
            if ($hash == $r->header('apiKey')) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

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


}