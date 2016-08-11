<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Zizaco\Entrust\Traits\EntrustUserTrait;
use App\UserMembership;

class User extends Authenticatable
{
    use EntrustUserTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'username',
        'email',
        'password',
    ];

    public static $messages = [
        'email.unique' => 'Please use an email that is not in the database',
    ];

    public static $attributeNames = [
        'email'         => 'Email address',
        'username'      => 'Username',
        'first_name'    => 'First Name',
        'middle_name'   => 'First Name',
        'last_name'     => 'Last Name',
        'password'      => 'Password',
        ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

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
                    'first_name'    => 'required|min:4|max:150',
                    'last_name'     => 'required|min:4|max:150',
                    'username'      => 'required|min:6|max:30|unique:users,username',
                    'password'      => 'required|min:8',
                    'email'         => 'required|email|email|unique:users',
                    'user_type'     => 'required|exists:roles,id',
                ];
            }
            case 'PUT':
            case 'PATCH':
            {
                return [
                    'first_name'=> 'required|min:4|max:150',
                    'last_name' => 'required|min:4|max:150',
                    'username'  => 'required|min:6|max:30|unique:users,username'.($id ? ",$id,id" : ''),
                    'password'  => 'required|min:8',
                    'email'     => 'required|email|email|unique:users,email'.($id ? ",$id,id" : ''),
                    'user_type' => 'required|exists:roles,id',
                ];
            }
            default:break;
        }
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

    public function membership(){
        return $this->hasMany('App\UserMembership');
    }

    public function attach_membership_plan(MembershipPlan $the_plan, User $signed_by){
        $user_plan = new UserMembership();
        //$user_plan->assign_plan($this, $the_plan, $signed_by);
        if ( $user_plan->create_new($this, $the_plan, $signed_by) ){
            return true;
        }
        else{
            return false;
        }
    }

    public function cancel_membership_plan(UserMembership $old_plan){
        if ($old_plan) {
            $old_plan->status = 'canceled';
            $old_plan->save();

            $memberRole = Role::where('name','=','front-member')->get()->first();
            $this->detachRole($memberRole);

            return true;
        }
        else{
            return false;
        }
    }
}
