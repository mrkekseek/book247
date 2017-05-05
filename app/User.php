<?php

namespace App;

use App\Http\Controllers\MembershipController;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
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
        'gender',
        'username',
        'email',
        'password',
        'country_id',
        'status',
        'password_api',
        'sso_user_id',
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
        'gender'        => 'Gender',
        'country_id'    => 'Country ID',
        'status'        => 'User Status'
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
                    'username'      => 'required|min:6|max:150|unique:users,username',
                    'password'      => 'required|min:8',
                    'email'         => 'required|email|unique:users',
                    'user_type'     => 'required|exists:roles,id',
                    'gender'        => 'in:M,F',
                    'status'        => 'in:active,suspended,deleted,pending'
                ];
            }
            case 'PUT':
            case 'PATCH':
            {
                return [
                    'first_name'=> 'required|min:2|max:150',
                    'last_name' => 'required|min:2|max:150',
                    'username'  => 'required|min:6|max:150|unique:users,username'.($id ? ",$id,id" : ''),
                    'password'  => 'required|min:8',
                    'email'     => 'required|email|unique:users,email'.($id ? ",$id,id" : ''),
                    'user_type' => 'required|exists:roles,id',
                    'gender'    => 'in:M,F',
                    'status'    => 'in:active,suspended,deleted,pending'
                ];
            }
            default:break;
        }
    }
           
    public static function boot()
    {
        parent::boot();
        static::creating(function($model)
        {            
            $attributes = $model->attributes;
            //dd($attributes);
            if (!empty($attributes['sso_user_id']))
            {
                return true;
            }
            else
            {
                $api_user = \App\Http\Libraries\ApiAuth::account_create($attributes);
                if ($api_user['success'])
                {
                    unset($model->password_api);
                    $model->sso_user_id = $api_user['data'];
                    return true;
                }
                return false;
            }
            
        });
        static::updating(function($model)
        {
            $attributes = $model->attributes;            
            $api_user = \App\Http\Libraries\ApiAuth::accounts_update($attributes);                        
            \App\Http\Libraries\Auth::$error = !empty($api_user['message']) ? $api_user['message'] : '';
            unset($model->birthday);
            return $api_user['success'];
        });
    }

    
    public function is_back_user() {        
        if ( $this->hasRole('front-user') || $this->hasRole('front-member') || $this->status != "active") {
            return false;
        }
        else {
            return true;
        }
    }

    public function is_front_user($all = true) {        
        if ( ($this->hasRole('front-user') || $this->hasRole('front-member')) && ($this->status == "active" || $all )) {
            return true;
        }
        else {
            return false;
        }
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

    public function get_active_membership(){
        $membership = UserMembership::where('user_id','=',$this->id)->whereIn('status',['active','suspended'])->get()->first();
        return $membership;
    }

    public function attach_membership_plan(MembershipPlan $the_plan, User $signed_by, $day_start = false, $contract_number = 0){
        if ($this->is_back_user()){
            return false;
        }

        $user_plan = new UserMembership();
        //$user_plan->assign_plan($this, $the_plan, $signed_by);
        if ( $user_plan->create_new($this, $the_plan, $signed_by, $day_start, $contract_number) ){
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

    public function update_membership_plan(UserMembership $old_plan, $start_date = '', $additional_values = [], $notes = []){
        if ($start_date==''){
            $start_date = Carbon::today();
        }

        if ($old_plan) {
            // insert a membership action related to this membership freeze
            $fillable = [
                'user_membership_id'    => $old_plan->id,
                'action_type'           => 'update',
                'additional_values'     => json_encode($additional_values),
                'start_date'    => $start_date->format('Y-m-d'),
                'end_date'      => $start_date->format('Y-m-d'),
                'added_by'      => Auth::user()->id,
                'notes'         => json_encode($notes),
                'processed'     => 0,
                'status'        => 'active'
            ];

            $userMembershipAction = Validator::make($fillable, UserMembershipAction::rules('POST'), UserMembershipAction::$message, UserMembershipAction::$attributeNames);
            if ($userMembershipAction->fails()){
                return array(
                    'success'   => false,
                    'title'     => 'Error Validating Action',
                    'errors'    => $userMembershipAction->getMessageBag()->toArray()
                );
            }

            $currentAction = UserMembershipAction::create($fillable);

            // if the freeze starts today, then we freeze the membership plan
            if (Carbon::today()->toDateString() == $start_date->toDateString()) {
                $additional_values = json_decode($currentAction->additional_values);

                // check the planned invoices that needs to be pushed out of the freeze period
                MembershipController::update_membership_rebuild_invoices($old_plan);

                // change active membership details : name, price, discount, restrictions
                $old_plan->membership_id    = $additional_values->new_membership_plan_id;
                $old_plan->membership_name  = $additional_values->new_membership_plan_name;
                $old_plan->price            = $additional_values->new_membership_plan_price;
                $old_plan->discount         = $additional_values->new_membership_plan_discount;
                $old_plan->membership_restrictions = $additional_values->new_membership_restrictions;
                $old_plan->save();

                // mark action as old
                $currentAction->status = 'old';
                $currentAction->save();
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

        if ($is_link==true){
            return 'data:'.$avatarType.';base64,'.base64_encode($avatarContent);
        }
        else{
            return [
                'file_location'     => $avatar->file_location,
                'avatar_content'    => $avatarContent,
                'avatar_type'       => $avatarType,
                'avatar_base64'     => 'data:'.$avatarType.';base64,'.base64_encode($avatarContent)
            ];
        }
    }

    /**
     * @param string $ord - order the notes ascending or descending
     * @param string $status - all, read, unread
     * @param bool   $update - true or false, updates the unread to read
     * @return array
     */
    public function get_public_notes($ord = 'DESC', $status = 'all', $update = false){
        $publicNote  = [];
        if ($status=='all'){
            $allNotes = GeneralNote::where('for_user_id','=',$this->id)->where('privacy','=','everyone')->orderBy('created_at','desc')->get();
        }
        else{
            $allNotes = GeneralNote::where('for_user_id','=',$this->id)->where('privacy','=','everyone')->where('status','=',$status)->orderBy('created_at','desc')->get();
        }

        if ($allNotes){
            foreach($allNotes as $note){
                $byUser = Cache::remember('user_table_'.$note->by_user_id,720,function() use ($note){
                    return User::where('id',$note->by_user_id)->get()->first();
                });

                $publicNote[] = [
                    'id'        => $note->id,
                    'by_user'   => $byUser->first_name.' '.$byUser->middle_name.' '.$byUser->last_name,
                    'by_user_avatar'=> $byUser->get_avatar_image(),
                    'by_user_link'  => $byUser,
                    'note_title'=> $note->note_title,
                    'note_body' => $note->note_body,
                    'note_type' => $note->note_type,
                    'status'    => $note->status,
                    'is_general'=> 1,
                    'dateAdded' => Carbon::createFromFormat('Y-m-d H:i:s', $note->created_at)->format('d-m-Y H:i'),
                    'addedOn'   => Carbon::createFromFormat('Y-m-d H:i:s', $note->created_at)->diffForHumans(),
                    'timestamp' => Carbon::createFromFormat('Y-m-d H:i:s', $note->created_at)->timestamp
                ];

                if ($note->status == 'unread' && $update == true){
                    $note->status = 'read';
                    $note->save();
                }
            }
        }

        $userID = $this->id;
        if ($status=='all'){
            $bookingNotes = BookingNote::whereHas('booking', function($query) use ($userID){
                $query->where('for_user_id','=',$userID);
            })->where('privacy','=','everyone')->orderBy('created_at','desc')->get();
        }
        else{
            $bookingNotes = BookingNote::whereHas('booking', function($query) use ($userID){
                $query->where('for_user_id','=',$userID);
            })->where('privacy','=','everyone')->where('status','=',$status)->orderBy('created_at','desc')->get();
        }

        if ($bookingNotes){
            foreach($bookingNotes as $note){
                $byUser = Cache::remember('user_table_'.$note->by_user_id,720,function() use ($note){
                    return User::where('id',$note->by_user_id)->get()->first();
                });

                $publicNote[] = [
                    'id'        => $note->id,
                    'by_user'   => $byUser->first_name.' '.$byUser->middle_name.' '.$byUser->last_name,
                    'by_user_avatar'=> $byUser->get_avatar_image(),
                    'by_user_link'  => $byUser,
                    'note_title'=> $note->note_title,
                    'note_body' => $note->note_body,
                    'note_type' => $note->note_type,
                    'status'    => $note->status,
                    'is_general'=> 0,
                    'dateAdded' => Carbon::createFromFormat('Y-m-d H:i:s', $note->created_at)->format('d-m-Y H:i'),
                    'addedOn'   => Carbon::createFromFormat('Y-m-d H:i:s', $note->created_at)->diffForHumans(),
                    'timestamp' => Carbon::createFromFormat('Y-m-d H:i:s', $note->created_at)->timestamp
                ];

                if ($note->status == 'unread' && $update == true){
                    $note->status = 'read';
                    $note->save();
                }
            }
        }

        if (sizeof($publicNote)>0){
            if ($ord == "DESC"){
                uasort($publicNote, 'self::notes_desc_cmp');
            }
            else{
                uasort($publicNote, 'self::notes_asc_cmp');
            }
        }

        return $publicNote;
    }

    public function get_private_notes($ord = 'DESC'){
        $privateNote = [];
        $allNotes = GeneralNote::where('for_user_id','=',$this->id)->where('privacy','!=','everyone')->orderBy('created_at','desc')->get();
        if ($allNotes){
            foreach($allNotes as $note){
                $byUser = Cache::remember('user_table_'.$note->by_user_id,720,function() use ($note){
                    return User::where('id',$note->by_user_id)->get()->first();
                });

                $privateNote[] = [
                    'id'        => $note->id,
                    'by_user'   => $byUser->first_name.' '.$byUser->middle_name.' '.$byUser->last_name,
                    'by_user_avatar'=> $byUser->get_avatar_image(),
                    'by_user_link'  => $byUser,
                    'note_title'=> $note->note_title,
                    'note_body' => $note->note_body,
                    'note_type' => $note->note_type,
                    'status'    => $note->status,
                    'is_general'=> 1,
                    'dateAdded' => Carbon::createFromFormat('Y-m-d H:i:s', $note->created_at)->format('d-m-Y H:i'),
                    'addedOn'   => Carbon::createFromFormat('Y-m-d H:i:s', $note->created_at)->diffForHumans(),
                    'timestamp' => Carbon::createFromFormat('Y-m-d H:i:s', $note->created_at)->timestamp
                ];
            }
        }

        $userID = $this->id;
        $bookingNotes = BookingNote::whereHas('booking', function($query) use ($userID){
                            $query->where('for_user_id','=',$userID);
                        })->where('privacy','!=','everyone')->orderBy('created_at','desc')->get();
        if ($bookingNotes){
            foreach($bookingNotes as $note){
                $byUser = Cache::remember('user_table_'.$note->by_user_id,720,function() use ($note){
                    return User::where('id',$note->by_user_id)->get()->first();
                });

                $privateNote[] = [
                    'id'        => $note->id,
                    'by_user'   => $byUser->first_name.' '.$byUser->middle_name.' '.$byUser->last_name,
                    'by_user_avatar'=> $byUser->get_avatar_image(),
                    'by_user_link'  => $byUser,
                    'note_title'=> $note->note_title,
                    'note_body' => $note->note_body,
                    'note_type' => $note->note_type,
                    'status'    => $note->status,
                    'is_general'=> 0,
                    'dateAdded' => Carbon::createFromFormat('Y-m-d H:i:s', $note->created_at)->format('d-m-Y H:i'),
                    'addedOn'   => Carbon::createFromFormat('Y-m-d H:i:s', $note->created_at)->diffForHumans(),
                    'timestamp' => Carbon::createFromFormat('Y-m-d H:i:s', $note->created_at)->timestamp
                ];
            }
        }

        if (sizeof($privateNote)>0){
            if ($ord == "DESC"){
                uasort($privateNote, 'self::notes_desc_cmp');
            }
            else{
                uasort($privateNote, 'self::notes_asc_cmp');
            }
        }

        return $privateNote;
    }

    /* Multi dimensional array sort START */
    function notes_desc_cmp($a, $b){
        if ($a['timestamp'] == $b['timestamp']) {
            return 0;
        }
        return ($a['timestamp'] > $b['timestamp']) ? -1 : 1;
    }

    function notes_asc_cmp($a, $b){
        if ($a['timestamp'] == $b['timestamp']) {
            return 0;
        }
        return ($a['timestamp'] < $b['timestamp']) ? -1 : 1;
    }
    /* Multi dimensional array sort END */

    public function get_preferred_location_name(){
        $setting = UserSettings::where('user_id','=',$this->id)->where('var_name','=','settings_preferred_location')->get()->first();
        if ($setting){
            $location = ShopLocations::where('id','=',$setting->var_value)->get()->first();
            if ($location){
                return $location->name;
            }
            else{
                return 'no location found';
            }
        }
        else{
            return 'no location set';
        }
    }

    public function get_signed_location_name(){
        $setting = UserSettings::where('user_id','=',$this->id)->where('var_name','=','registration_signed_location')->get()->first();
        if ($setting){
            $location = ShopLocations::where('id','=',$setting->var_value)->get()->first();
            if ($location){
                return $location->name;
            }
            else{
                return 'no location found';
            }
        }
        else{
            return 'no registration location';
        }
    }

    public function set_general_setting($key, $value){
        try {
            $fillable = [
                'user_id'   => $this->id,
                'var_name'  => $key,
            ];
            $generalSetting = UserSettings::firstOrNew($fillable);
            $generalSetting->var_value = $value;
            $generalSetting->save();

            return true;
        }
        catch (\Exception $ex){
            return false;
        }
    }

    public function get_general_setting($key){
        $setting = UserSettings::where('user_id','=',$this->id)->where('var_name','=',$key)->get()->first();
        if ($setting){
            return $setting->var_value;
        }
        else{
            return false;
        }
    }

    public function get_next_customer_number($requested_number = 0){
        if (is_numeric($requested_number)){
            $requested_number = intval($requested_number);
        }
        else{
            $requested_number = 0;
        }

        if ($requested_number!=0){
            $get_number = PersonalDetail::where('customer_number','=',$requested_number)->get()->first();
            if (!$get_number){
                return $requested_number;
            }
        }

        $current_last_customer_number = PersonalDetail::whereNotNull('customer_number')->orderBy('customer_number', 'DESC')->take('1')->first();
        if ($current_last_customer_number){
            $next_number = $current_last_customer_number->customer_number + 1;
        }
        else{
            $next_number = 10001;
        }

        return $next_number;
    }

    public function count_own_friends(){
        $member = $this;
        $own_friends  = UserFriends::
                        select('id')->where(function($query) use ($member) {
                            $query->where('user_id','=',$member->id);
                            $query->orWhere('friend_id','=',$member->id);
                        })->count();

        return $own_friends;
    }

    public function count_own_active_bookings(){
        $new_bookings = Booking::
            select('id')
            ->where('for_user_id','=',$this->id)
            ->whereIn('status',['active'])
            ->count();

        return $new_bookings;
    }

    public function count_own_old_bookings(){
        $old_bookings = Booking::
            select('id')
            ->where('for_user_id','=',$this->id)
            ->whereIn('status',['paid','unpaid','old','no_show'])
            ->count();

        return $old_bookings;
    }

    public function count_own_cancelled_bookings(){
        $cancelled_bookings = Booking::
            select('id')
            ->where('for_user_id','=',$this->id)
            ->whereIn('status',['canceled'])
            ->count();

        return $cancelled_bookings;
    }

    /**
     * Detach all roles from a user
     *
     * @return object
     */
    public function detachAllRoles()
    {
        DB::table('role_user')->where('user_id', $this->id)->delete();

        return $this;
    }
}