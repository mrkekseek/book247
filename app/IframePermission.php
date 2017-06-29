<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class IframePermission extends Model
{

    protected $table ="iframe_permissions";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'permission_token',
    ];

    public static function createPermission($user_id){
        $permission = new self;
        $token = str_random(32);
        $permission->fill([
            'user_id' => $user_id,
            'permission_token' => $token
        ]);
        $permission->save();
        return $token;
    }

    public static function removePermission($user_id){
        $permission = self::where('user_id',$user_id)->first();
        if(isset($permission)) {
            $permission->delete();
        }
    }

    public static function validate($user_id , $permission_token){
        $permission = self::where('user_id',$user_id)->first();
        if($permission->permission_token == $permission_token){
            $permission->delete();
            return true;
        } else {
            return false;
        }
    }
}
