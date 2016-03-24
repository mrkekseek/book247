<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserDocuments extends Model
{
    protected $table = 'users_documents';
    protected $primaryKey = 'id';

    protected $fillable = array(
        'user_id',
        'file_name',
        'file_location',
        'file_type',
        'category',
        'comments',
    );

    public function user(){
        return $this->belongsTo('App\User');
    }
}
