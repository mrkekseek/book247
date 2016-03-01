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
    );

    public function user(){
        return $this->belongsTo('App\User');
    }
}
