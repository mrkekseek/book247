<?php
namespace App\Http\Controllers\Federation;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\RequestValidator;
use App\User as UserModel;


class FederationApi extends Controller {

    public function federation_member_has_valid_license(Request $r){
        if(RequestValidator::validate($r)){
            return json_encode(array(
                'code' => 1,
                'isValid' => true
            ),JSON_FORCE_OBJECT);
        } else {
            return json_encode(array(
                'code' => 2,
                'message' => 'Permission denied.'
            ),JSON_FORCE_OBJECT);
        }
    }


    public function federation_list_of_licenses(Request $r){
        if(RequestValidator::validate($r)){

        } else {

        }
    }



    public function federation_buy_license(Request $r){
        if(RequestValidator::validate($r)){
            $user = isset($r->memberSSOid) ? UserModel::where('sso_user_id',$r->memberSSOid)->first() : null;
            if(isset($r->memberSSOid) && $user) {
                return json_encode(array(
                    'code' => 1,
                    'iFrameUrl' => route('buy_license',[ 'sso_id' => $r->memberSSOid,'membership_id' => $r->activity ])
                ),JSON_FORCE_OBJECT);
            } else {
                return json_encode(array(
                    'code' => 2,
                    'message' => 'Invalid request.'
                ),JSON_FORCE_OBJECT);
            }
        } else {
            return json_encode(array(
                'code' => 2,
                'message' => 'Permission denied.'
            ),JSON_FORCE_OBJECT);
        }
    }

}