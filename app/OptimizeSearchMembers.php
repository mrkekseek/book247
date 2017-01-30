<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

use App\User;
use App\Role;
use App\Permission;
use App\UserMembership;
use Validator;

class OptimizeSearchMembers extends Model
{
    protected $table = 'optimize_search_members';

    public static $attributeNames = array(
        'user_id'       => 'User ID',
        'first_name'    => 'First Name',
        'middle_name'   => 'Middle Name',
        'last_name'     => 'Last Name',

        'email'     => 'Email',
        'phone'     => 'Phone No.',

        'city'      => 'City',
        'region'    => 'Region',

        'membership_name'       => 'Membership Name',
        'user_profile_image'    => 'User Profile Image',
        'base64_avatar_image'   => 'Avatar Base64 Image',

        'user_link_details'     => 'User Link',
    );

    public static $message = array();

    protected $fillable = [
        'user_id',
        'first_name',
        'middle_name',
        'last_name',
        'email',
        'phone',
        'city',
        'region',
        'membership_name',
        'user_profile_image',
        'user_link_details'
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
                    'user_id'               => 'required|numeric|min:1',
                    'first_name'            => 'required|min:2|max:150',
                    'middle_name'           => '',
                    'last_name'             => 'required|min:2|max:150',
                    'email'                 => 'required|email',
                    'phone'                 => 'required',
                    'city'                  => '',
                    'region'                => '',
                    'membership_name'       => 'min:3',
                    'user_profile_image'    => 'min:3',
                    'user_link_details'     => 'required',
                ];
            }
            case 'PUT':
            case 'PATCH':
            {
                return [
                    'user_id'               => 'required|numeric|min:1',
                    'first_name'            => 'required|min:2|max:150',
                    'middle_name'           => '',
                    'last_name'             => 'required|min:2|max:150',
                    'email'                 => 'required|email',
                    'phone'                 => 'required',
                    'city'                  => '',
                    'region'                => '',
                    'membership_name'       => 'min:3',
                    'user_profile_image'    => 'min:3',
                    'user_link_details'     => 'required',
                ];
            }
            default:break;
        }
    }

    private function rebuild_search_table(){
        ini_set('max_execution_time', 300);

        DB::disableQueryLog();
        $query = DB::table('users')
            ->select('users.first_name','users.middle_name','users.last_name','users.id','users.email','personal_details.mobile_number')
            ->leftjoin('personal_details','personal_details.user_id','=','users.id')
            ->leftjoin('role_user', 'users.id', '=', 'role_user.user_id')
            ->whereIn('role_user.role_id',['5','6'])
            ->groupBy('users.id');

        $results = $query->get();
        if ($results){
            foreach($results as $result){
                $user_temp = User::where('id','=',$result->id)->get()->first();
                $user_link = route('admin/front_users/view_user',['id'=>$user_temp->id]);
                //$avatar = $user_temp->get_avatar_image();

                $userMembership = UserMembership::where('user_id','=',$result->id)->where('status','=','active')->get()->first();
                if ($userMembership){
                    $activeMembership = $userMembership->membership_name;
                }
                else{
                    $activeMembership = 'No Active Membership';
                }

                $fillable = [
                    'user_id'           =>$result->id,
                    'first_name'        => $result->first_name,
                    'middle_name'       => $result->middle_name,
                    'last_name'         => $result->last_name,
                    'email'             => $result->email,
                    'phone'             => $result->mobile_number,
                    'city'              => '',
                    'region'            => '',
                    'membership_name'       => $activeMembership,
                    'user_profile_image'    => asset('assets/pages/img/avatars/team'.rand(1,10).'.jpg'),
                    'base64_avatar_image'   => '',
                    'user_link_details'     => $user_link
                ];

                $validator = Validator::make($fillable, OptimizeSearchMembers::rules('POST'), OptimizeSearchMembers::$message, OptimizeSearchMembers::$attributeNames);
                if ($validator->fails()){
                    echo json_encode($validator->getMessageBag()->toArray());
                }
                else{
                    $new_insert = new OptimizeSearchMembers();
                    $new_insert->fill($fillable);
                    $new_insert->save();
                }

                unset($fillable);
            }
        }
    }

    private function reset_search_table(){
        DB::table('optimize_search_members')->truncate();
    }

    private function update_search_table($last_date){
        ini_set('max_execution_time', 300);

        DB::disableQueryLog();
        $query = DB::table('users')
            ->select('users.first_name','users.middle_name','users.last_name','users.id','users.email','personal_details.mobile_number')
            ->leftjoin('personal_details','personal_details.user_id','=','users.id')
            ->leftjoin('role_user', 'users.id', '=', 'role_user.user_id')
            ->whereIn('role_user.role_id',['5','6'])
            ->where('users.created_at','>=',$last_date)
            ->groupBy('users.id');

        $results = $query->get();
        if ($results){
            foreach($results as $result){
                $user_temp = User::where('id','=',$result->id)->get()->first();
                $user_link = route('admin/front_users/view_user',['id'=>$user_temp->id]);
                //$avatar = $user_temp->get_avatar_image();

                $userMembership = UserMembership::where('user_id','=',$result->id)->where('status','=','active')->get()->first();
                if ($userMembership){
                    $activeMembership = $userMembership->membership_name;
                }
                else{
                    $activeMembership = 'No Active Membership';
                }

                $fillable = [
                    'user_id'           => $result->id,
                    'first_name'        => $result->first_name,
                    'middle_name'       => $result->middle_name,
                    'last_name'         => $result->last_name,
                    'email'             => $result->email,
                    'phone'             => $result->mobile_number,
                    'city'              => '',
                    'region'            => '',
                    'membership_name'       => $activeMembership,
                    'user_profile_image'    => asset('assets/pages/img/avatars/team'.rand(1,10).'.jpg'),
                    'base64_avatar_image'   => '',
                    'user_link_details'     => $user_link
                ];

                $validator = Validator::make($fillable, OptimizeSearchMembers::rules('POST'), OptimizeSearchMembers::$message, OptimizeSearchMembers::$attributeNames);
                if ($validator->fails()){
                    echo json_encode($validator->getMessageBag()->toArray());
                }
                else {
                    $new_or_old_insert = OptimizeSearchMembers::firstOrNew(['user_id' => $result->id]);
                    $new_or_old_insert->fill($fillable);
                    $new_or_old_insert->save();
                }

                unset($fillable);
            }
        }
    }

    private function update_search_table_with_user($ids){
        ini_set('max_execution_time', 300);

        DB::disableQueryLog();
        $query = DB::table('users')
            ->select('users.first_name','users.middle_name','users.last_name','users.id','users.email','personal_details.mobile_number')
            ->leftjoin('personal_details','personal_details.user_id','=','users.id')
            ->leftjoin('role_user', 'users.id', '=', 'role_user.user_id')
            ->whereIn('role_user.role_id',['5','6'])
            ->whereIn('users.id',$ids)
            ->groupBy('users.id');

        $results = $query->get();
        if ($results){
            foreach($results as $result){
                $user_temp = User::where('id','=',$result->id)->get()->first();
                $user_link = route('admin/front_users/view_user',['id'=>$user_temp->id]);
                //$avatar = $user_temp->get_avatar_image();

                $userMembership = UserMembership::where('user_id','=',$result->id)->where('status','=','active')->get()->first();
                if ($userMembership){
                    $activeMembership = $userMembership->membership_name;
                }
                else{
                    $activeMembership = 'No Active Membership';
                }

                $fillable = [
                    'user_id'           => $result->id,
                    'first_name'        => $result->first_name,
                    'middle_name'       => $result->middle_name,
                    'last_name'         => $result->last_name,
                    'email'             => $result->email,
                    'phone'             => $result->mobile_number,
                    'city'              => '',
                    'region'            => '',
                    'membership_name'       => $activeMembership,
                    'user_profile_image'    => asset('assets/pages/img/avatars/team'.rand(1,10).'.jpg'),
                    'base64_avatar_image'   => '',
                    'user_link_details'     => $user_link
                ];

                $validator = Validator::make($fillable, OptimizeSearchMembers::rules('POST'), OptimizeSearchMembers::$message, OptimizeSearchMembers::$attributeNames);
                if ($validator->fails()){
                    echo json_encode($validator->getMessageBag()->toArray());
                }
                else {
                    $new_or_old_insert = OptimizeSearchMembers::firstOrNew(['user_id' => $result->id]);
                    $new_or_old_insert->fill($fillable);
                    $new_or_old_insert->save();
                }

                unset($fillable);
            }
        }
    }

    public function clean_rebuild_table(){
        $this->reset_search_table();
        $this->rebuild_search_table();
    }

    public function add_missing_members($ids = []){
        // get last added date for optimize_search_members (created_at)

        if (sizeof($ids)==0){
            $last_optimized_entry = OptimizeSearchMembers::orderBy('created_at','DESC')->get()->first();

            if($last_optimized_entry){
                $lastDate = $last_optimized_entry->created_at;
            }
            else{
                $lastDate = Carbon::today()->format('Y-m-d H:i:s');
            }

            $this->update_search_table($lastDate);
        }
        else{
            $this->update_search_table_with_user($ids);
        }
    }
}
