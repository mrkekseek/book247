<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProfessionalDetail extends Model
{
    protected $table = 'professional_details';
    protected $primaryKey = 'id';

    protected $fillable = array(
        'user_id',
        'professional_email',
        'job_title',
        'profession',
        'description',
    );

    public function user(){
        return $this->belongsTo('App\User');
    }
}
