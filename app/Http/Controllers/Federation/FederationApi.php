<?php
namespace App\Http\Controllers\Federation;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\RequestValidator;
use App\User as UserModel;
use App\IframePermission;


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
                $token = IframePermission::createPermission($r->memberSSOid);
                return json_encode(array(
                    'code' => 1,
                    'iFrameUrl' => route('buy_license',[ 'token' => $token , 'sso_id' => $r->memberSSOid,'membership_id' => isset($r->membership_id) ? $r->membership_id : -1 ]).'?redirect_url='.$r->get('return_url')
                ),JSON_FORCE_OBJECT);
            } else {
                return json_encode(array(
                    'code' => 2,
                    'message' => 'Invalid request. User does not exist.'
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