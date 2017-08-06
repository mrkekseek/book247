<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PersonalDetail extends Model
{
    protected $table = 'personal_details';
    protected $primaryKey = 'id';

    public static $attributeNames = [
        'user_id'           => 'User ID',
        'personal_email'    => 'Personal Email',
        'mobile_number'     => 'Mobile Number',
        'date_of_birth'     => 'Date of birth',
        'about_info'        => 'About Info',
        'bank_acc_no'       => 'Bank account number',
        'social_sec_no'     => 'Social security number',
        'customer_number'   => 'Customer Number'
    ];

    protected $fillable = array(
        'user_id',
        'personal_email',
        'mobile_number',
        'date_of_birth',
        'about_info',
        'bank_acc_no',
        'social_sec_no',
        'customer_number'
    );

    public static $messages = [];

    public static function rules($method, $id=0){
        switch($method){
            case 'GET':
            case 'DELETE':
            {
                return [];
            }
            case 'POST':
            {
                return [
                    //'user_id'           => 'required|exists:users,id',
                    //'personal_email'    => 'required|unique:personal_details,personal_email|exists:users,email',
                    'personal_email'    => 'required|unique:personal_details,personal_email',
                    'mobile_number'     => 'required|min:3|unique:personal_details,mobile_number',
                    'date_of_birth'     => 'required|date',
                    'about_info'        => '',
                    'bank_acc_no'       => '',
                    'social_sec_no'     => '',
                    'customer_number'   => 'required|numeric|unique:personal_details,customer_number',
                ];
            }
            case 'PUT':
            {
                return [
                    'user_id'           => 'required|exists:users,id',
                    'personal_email'    => 'required|unique:personal_details,personal_email'.($id ? ','.$id.',user_id' : ''),
                    'mobile_number'     => 'required|min:3|unique:personal_details,mobile_number'.($id ? ",$id,user_id" : ''),
                    'date_of_birth'     => 'required|date',
                    'about_info'        => '',
                    'bank_acc_no'       => '',
                    'social_sec_no'     => '',
                    'customer_number'   => 'numeric|unique:personal_details,customer_number'.($id ? ",$id,user_id" : '')
                ];
            }
            case 'PATCH':
            {
                return [
                    'user_id'           => 'required|exists:users,id',
                    'personal_email'    => 'required|unique:personal_details,personal_email'.($id ? ",$id,user_id" : ''),
                    'mobile_number'     => 'required|min:3|unique:personal_details,mobile_number'.($id ? ",$id,user_id" : ''),
                    'date_of_birth'     => 'required|date',
                    'about_info'        => '',
                    'bank_acc_no'       => '',
                    'social_sec_no'     => '',
                    'customer_number'   => 'required|numeric|unique:personal_details,customer_number'.($id ? ",$id,user_id" : '')
                ];
            }
            default:break;
        }
    }

    public function user(){
        return $this->belongsTo('App\User');
    }
}
