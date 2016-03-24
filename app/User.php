<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Zizaco\Entrust\Traits\EntrustUserTrait;

class User extends Authenticatable
{
    use EntrustUserTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'middle_name', 'last_name', 'username', 'email', 'password',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function hasRole($name){

    }

    public function canDo($permission){

    }

    public function ability($roles, $permissions, $options){

    }

    public function roles()
    {
        return $this->belongsToMany('App\Role');
    }

    public function Addresses(){
        return $this->hasMany('App\Address');
    }

    public function ProfessionalDetail(){
        return $this->hasOne('App\ProfessionalDetail');
    }

    public function PersonalDetail(){
        return $this->hasOne('App\PersonalDetail');
    }

    public function avatar(){
        return $this->hasOne('App\UserAvatars');
    }

    public function documents(){
        return $this->hasMany('App\UserDocuments');
    }
}
