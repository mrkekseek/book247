<?php

namespace App;

use App\Http\Controllers\MembershipController;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;
use Zizaco\Entrust\Traits\EntrustUserTrait;
use App\UserMembership;
use App\UserAvatars;
use Storage;
use Validator;

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
        'gender'
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
        'gender'        => 'Gender'
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
                    'first_name'    => 'required|min:2|max:150',
                    'last_name'     => 'required|min:2|max:150',
                    'username'      => 'required|min:6|max:30|unique:users,username',
                    'password'      => 'required|min:8',
                    'email'         => 'required|email|email|unique:users',
                    'user_type'     => 'required|exists:roles,id',
                    'gender'        => 'in:M,F'
                ];
            }
            case 'PUT':
            case 'PATCH':
            {
                return [
                    'first_name'=> 'required|min:2|max:150',
                    'last_name' => 'required|min:2|max:150',
                    'username'  => 'required|min:6|max:30|unique:users,username'.($id ? ",$id,id" : ''),
                    'password'  => 'required|min:8',
                    'email'     => 'required|email|email|unique:users,email'.($id ? ",$id,id" : ''),
                    'user_type' => 'required|exists:roles,id',
                    'gender'    => 'in:M,F'
                ];
            }
            default:break;
        }
    }

    public function is_back_user()
    {
        foreach ($this->roles()->get() as $role)
        {
            if ($role->name != 'front-user' && $role->name != 'front-member')
            {
                return true;
            }
        }

        return false;
    }

    public function is_front_user()
    {
        foreach ($this->roles()->get() as $role)
        {
            if ($role->name == 'front-user' || $role->name == 'front-member')
            {
                return true;
            }
        }

        return false;
    }

    public function membership_status(){
        $membership = UserMembership::where('user_id','=',$this->id)->whereIn('status',['active','unpaid','suspended'])->orderBy('created_at','DESC')->get()->first();
        if ($membership){
            if ($membership->status=='active'){
                return $membership->membership_name;
            }
            elseif($membership->status=='suspended'){
                return $membership->membership_name.' - frozen';
            }
            else{
                return $membership->membership_name.' - unpaid';
            }
        }
        else{
            return 'normal user';
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

    public function freeze_membership_plan(UserMembership $old_plan, $from_date, $to_date){
        if ($old_plan) {
            // insert a membership action related to this membership freeze
            $fillable = [
                'user_membership_id'    => $old_plan->id,
                'action_type'   => 'freeze',
                'start_date'    => $from_date->format('Y-m-d'),
                'end_date'      => $to_date->format('Y-m-d'),
                'added_by'      => Auth::user()->id,
                'notes'         => json_encode([]),
                'processed'     => 0,
                'status'        => 'active'
            ];

            $userMembershipAction = Validator::make($fillable, UserMembershipAction::rules('POST'), UserMembershipAction::$message, UserMembershipAction::$attributeNames);
            if ($userMembershipAction->fails()){
                //return $validator->errors()->all();
                return array(
                    'success'   => false,
                    'title'     => 'Error Validating Action',
                    'errors'    => $userMembershipAction->getMessageBag()->toArray()
                );
            }

            UserMembershipAction::create($fillable);
            // get first next invoice and check if the start date is included there
            $nextInvoice = UserMembershipInvoicePlanning::where('user_membership_id','=',$old_plan->id)->where('status','=','pending')->orderBy('issued_date','ASC')->get()->first();
            if ($nextInvoice){
                // if the freeze starts between next invoices start/end date, then we rebuild the invoices
                $invoiceStart   = Carbon::createFromFormat('Y-m-d H:i:s', $nextInvoice->issued_date.' 00:00:00');
                $invoiceEnd     = Carbon::createFromFormat('Y-m-d H:i:s', $nextInvoice->last_active_date.' 00:00:00');

                if ($from_date->between($invoiceStart, $invoiceEnd)) {
                    // check the planned invoices that needs to be pushed out of the freeze period
                    //MembershipController::freeze_membership_rebuild_invoices($old_plan);
                }
            }

            return true;
        }
        else{
            return false;
        }
    }

    public function cancel_membership_plan(UserMembership $old_plan, $cancel_date = '', $last_date = ''){
        if ($cancel_date == ''){
            $cancel_date = Carbon::today();
            $last_date   = Carbon::tomorrow();
        }
        else{
            try {
                $cancel_date = Carbon::createFromFormat('Y-m-d',$cancel_date);
                $last_date = Carbon::createFromFormat('Y-m-d',$last_date);
            }
            catch (\Exception $ex){
                $cancel_date = Carbon::today();
                $last_date   = Carbon::tomorrow();
            }
        }

        if ($old_plan) {
            // insert a membership action related to this membership freeze
            $fillable = [
                'user_membership_id'    => $old_plan->id,
                'action_type'   => 'cancel',
                'start_date'    => $cancel_date->format('Y-m-d'),
                'end_date'      => $last_date->format('Y-m-d'),
                'added_by'      => Auth::user()->id,
                'notes'         => json_encode([]),
                'processed'     => 0,
                'status'        => 'active'
            ];

            $userMembershipAction = Validator::make($fillable, UserMembershipAction::rules('POST'), UserMembershipAction::$message, UserMembershipAction::$attributeNames);
            if ($userMembershipAction->fails()){
                //return $validator->errors()->all();
                return array(
                    'success'   => false,
                    'title'     => 'Error Validating Action',
                    'errors'    => $userMembershipAction->getMessageBag()->toArray()
                );
            }

            UserMembershipAction::create($fillable);

            // if the freeze starts today, then we freeze the membership plan
            if (Carbon::today()->toDateString() == $cancel_date->toDateString()) {
                // check the planned invoices that needs to be pushed out of the freeze period
                MembershipController::cancel_membership_rebuild_invoices($old_plan);

                $old_plan->status = 'canceled';
                $old_plan->save();
                $memberRole = Role::where('name','=','front-member')->get()->first();
                $this->detachRole($memberRole);
            }

            return true;
        }
        else{
            return false;
        }
    }

    public function get_membership_restrictions(){
        $active_membership = UserMembership::where('user_id','=',$this->id)->where('status','=','active')->get()->first();
        if ($active_membership){
            $restrictions = $active_membership->get_plan_restrictions();
        }
        else{
            $default_membership = MembershipPlan::where('id','=',1)->get()->first();
            if ($default_membership){
                $restrictions = $default_membership->get_restrictions(true);
            }
            else{
                $restrictions = [];
            }
        }

        return $restrictions;
    }

    public function get_avatar_image($is_link = false){
        $avatar = $this->avatar;
        if (!$avatar) {
            $avatar = new UserAvatars();
            $avatar->file_location = 'employees/default/avatars/';
            $avatar->file_name = 'default.jpg';
        }

        if ($this->is_back_user()){
            if (Storage::disk('local')->exists($avatar->file_location . $avatar->file_name)) {
                $avatarContent  = Storage::disk('local')->get($avatar->file_location . $avatar->file_name);
                $avatarType     = Storage::disk('local')->mimeType($avatar->file_location . $avatar->file_name);
            }
        }
        elseif($this->is_front_user()){
            if (Storage::disk('local')->exists($avatar->file_location . $avatar->file_name)) {
                $avatarContent  = Storage::disk('local')->get($avatar->file_location . $avatar->file_name);
                $avatarType     = Storage::disk('local')->mimeType($avatar->file_location . $avatar->file_name);
            }
        }

        if (!isset($avatarContent) || !isset($avatarType)){
            $avatarContent      = Storage::disk('local')->get('members/default/avatars/default.jpg');
            $avatarType         = Storage::disk('local')->mimeType('members/default/avatars/default.jpg');
        }

        return [
            'file_location'     => $avatar->file_location,
            'avatar_content'    => $avatarContent,
            'avatar_type'       => $avatarType,
            'avatar_base64'     => 'data:'.$avatarType.';base64,'.base64_encode($avatarContent)
        ];
    }
}
