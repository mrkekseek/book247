<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PersonalDetail extends Model
{
    protected $table = 'personal_details';
    protected $primaryKey = 'id';

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
                    'user_id'           => 'required|exists:users,id',
                    'personal_email'    => 'required|unique:personal_details,personal_email|exists:users,email',
                    'mobile_number'     => 'required',
                    'date_of_birth'     => 'required|date',
                    'about_info'        => 'min:10',
                    'bank_acc_no'       => 'min:10',
                    'social_sec_no'     => 'min:10',
                    'customer_number'   => 'required|numeric|unique:personal_details,customer_number',
                ];
            }
            case 'PUT':
            case 'PATCH':
            {
                return [
                    'user_id'           => 'required|exists:users,id',
                    'personal_email'    => 'required|unique:personal_details,personal_email|exists:users,email'.($id ? ",$id,id" : ''),
                    'mobile_number'     => 'required',
                    'date_of_birth'     => 'required|date',
                    'about_info'        => 'min:10',
                    'bank_acc_no'       => 'min:10',
                    'social_sec_no'     => 'min:10',
                    'customer_number'   => 'required|numeric|unique:personal_details,customer_number'.($id ? ",$id,id" : '')
                ];
            }
            default:break;
        }
    }

    public function user(){
        return $this->belongsTo('App\User');
    }
}
