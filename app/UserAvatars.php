<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserAvatars extends Model
{
    protected $table = 'user_avatars';
    protected $primaryKey = 'id';

    protected $fillable = array(
        'user_id',
        'file_name',
        'file_location',
        'width',
        'height',
    );

    public function user(){
        return $this->belongsTo('App\User');
    }
}
