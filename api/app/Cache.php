<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class Cache extends Model
{

    protected $table ="cache";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'request_type', 'response',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    public static function  get($request_type){
        return Cache::where('request_type',$request_type)->first();
    }
    public static function set($request_type, $data){
        $req = Cache::where('request_type',$request_type)->first();
        if ($req) {
            $req->response = $data;
        } else {
            $req = new self();
            $req->request_type = $request_type;
            $req->response = $data;
        }
        $req->save();
    }
}
