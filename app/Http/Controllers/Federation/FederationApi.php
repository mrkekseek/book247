<?php
namespace App\Http\Controllers\Federation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Validator;


class FederationApi extends Controller {

    public function federation_member_has_valid_license(Request $r){
        if(Validator::validate($r)){
            return json_encode(array(
                'code' => 1,
                'isValid' => true
            ),JSON_FORCE_OBJECT);
        } else {
            return json_encode(array(
                'code' => 2,
                'message' => 'Some error occurred.'
            ),JSON_FORCE_OBJECT);
        }
    }


    public function federation_list_of_licenses(Request $r){
        if(Validator::validate($r)){

        } else {

        }
    }



    public function federation_buy_license(Request $r){
        if(Validator::validate($r)){

        } else {

        }
    }

}