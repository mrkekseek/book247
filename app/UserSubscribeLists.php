<?php

namespace App;

use App\Http\Controllers\AppSettings;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Validator;

class UserSubscribeLists extends Model
{
    protected $table = 'user_subscribe_lists';

    public function user()
    {
        return $this->belongsTo('App\User');
    }
    
}
