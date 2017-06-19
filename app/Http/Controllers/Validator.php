<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Validator
{
    public static function validate(Request $r)
    {
        $key = env('APIKEY');
        if ($key) {
            $hash = self::encrypt(json_encode($r->all()), $key);
            if ($hash == $r->header('apiKey')) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    private static function encrypt($data, $key)
    {
        return base64_encode(hash_hmac('sha256', $data, $key, TRUE));
    }


}