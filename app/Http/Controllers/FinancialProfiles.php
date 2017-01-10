<?php

namespace App\Http\Controllers;

use App\FinancialProfile;
use Validator;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Regulus\ActivityLog\Models\Activity;
use Webpatser\Countries\Countries;

class FinancialProfiles extends Controller
{
    public function list_all(){
        $user = Auth::user();
        if (!$user || !$user->is_back_user()) {
            return redirect()->intended(route('admin/login'));
        }

        $all_profiles = [];
        $financial_profiles = FinancialProfile::orderBy('profile_name', 'ASC')->get();
        if ($financial_profiles){
            $i = 1;
            foreach($financial_profiles as $profile){
                $all_profiles[$i] = [
                    'id'            => $profile->id,
                    'profile_name'  => $profile->profile_name,
                    'company_name'  => $profile->company_name,
                    'bank_name'     => $profile->bank_name,
                    'bank_account'  => $profile->bank_account
                ];
                $i++;
            }
        }

        $breadcrumbs = [
            'Home'              => route('admin'),
            'Administration'    => route('admin'),
            'Back End User'     => route('admin'),
            'Permissions'        => '',
        ];
        $text_parts  = [
            'title'     => 'All Financial Profiles',
            'subtitle'  => 'add/edit/view profiles',
            'table_head_text1' => 'Financial Profiles'
        ];
        $sidebar_link= 'admin-settings-financial_profiles-list_all';

        return view('admin/settings/financial_profiles_list_all', [
            'breadcrumbs' => $breadcrumbs,
            'text_parts'  => $text_parts,
            'in_sidebar'  => $sidebar_link,
            'all_profiles'=> $all_profiles
        ]);
    }

    public function add_shop_financial_profile(){
        $user = Auth::user();
        if (!$user || !$user->is_back_user()) {
            return redirect()->intended(route('admin/login'));
        }
        elseif (!$user->can('create-financial-profile')){
            return redirect()->intended(route('admin/error/permission_denied'));
        }

        $countries = Countries::orderBy('name')->get();

        $breadcrumbs = [
            'Home'              => route('admin'),
            'Administration'    => route('admin'),
            'Back End User'     => route('admin'),
            'Permissions'        => '',
        ];
        $text_parts  = [
            'title'     => 'Add new financial profile',
            'subtitle'  => '',
            'table_head_text1' => 'Financial Profile - Create New'
        ];
        $sidebar_link = 'admin-settings-financial_profiles-add_new';

        return view('admin/settings/financial_profiles_add_new', [
            'breadcrumbs' => $breadcrumbs,
            'text_parts'  => $text_parts,
            'in_sidebar'  => $sidebar_link,
            'cash_terminals' => [],
            'shops' => [],
            'countries'   => $countries
        ]);
    }

    public function store_shop_financial_profile(Request $request){
        $user = Auth::user();
        if (!$user || !$user->is_back_user()) {
            //return redirect()->intended(route('admin/login'));
            return [
                'success' => false,
                'errors'  => 'Error while trying to authenticate. Login first then use this function.',
                'title'   => 'Not logged in'];
        }
        elseif (!$user->can('create-financial-profile')){
            return [
                'success'   => false,
                'errors'    => 'You don\'t have permission to access this page',
                'title'     => 'Permission Error'];
            //return redirect()->intended(route('admin/error/permission_denied'));
        }

        $vars = $request->only('profile_name', 'company_name', 'bank_name', 'bank_account', 'organisation_number', 'address1', 'address2', 'city', 'postal_code', 'region', 'country');

        $fillable = [
            'profile_name'  => $vars['profile_name'],
            'company_name'  => $vars['company_name'],
            'bank_name'     => $vars['bank_name'],
            'bank_account'  => $vars['bank_account'],
            'organisation_number'   => $vars['organisation_number'],
            'address1'      => $vars['address1'],
            'address2'      => $vars['address2'],
            'city'          => $vars['city'],
            'postal_code'   => $vars['postal_code'],
            'region'        => $vars['region'],
            'country_id'    => $vars['country'],
        ];
        $validator = Validator::make($fillable, FinancialProfile::rules('POST'), FinancialProfile::$message, FinancialProfile::$attributeNames);
        if ($validator->fails()){
            return array(
                'success' => false,
                'title'  => 'Error validating input information',
                'errors' => $validator->getMessageBag()->toArray()
            );
        }

        try {
            $finance_profile = FinancialProfile::create($fillable);

            Activity::log([
                'contentId'     => $user->id,
                'contentType'   => 'financial_profile',
                'action'        => 'New Financial Profile',
                'description'   => 'New financial profile created : '.$finance_profile->id,
                'details'       => 'Created by user : '.$user->id,
                'updated'       => false,
            ]);

            return [
                'success' => true,
                'message' => 'Financial profile created. You can assign it to one of your locations',
                'title'   => 'Financial Profile Created'
            ];
        }
        catch (Exception $e) {
            return Response::json(['error' => 'Booking Error'], Response::HTTP_CONFLICT);
        }
    }

    public function show_shop_financial_profile($id){

    }

    public function edit_shop_financial_profile($id){
        $user = Auth::user();
        if (!$user || !$user->is_back_user()) {
            return redirect()->intended(route('admin/login'));
        }
        elseif (!$user->can('create-financial-profile')){
            return redirect()->intended(route('admin/error/permission_denied'));
        }

        $profile = FinancialProfile::find($id);
        if (!$profile){
            return redirect(route('admin/error/not_found'));
        }
        $countries = Countries::orderBy('name')->get();

        $breadcrumbs = [
            'Home'              => route('admin'),
            'Administration'    => route('admin'),
            'Back End User'     => route('admin'),
            'Permissions'        => '',
        ];
        $text_parts  = [
            'title'     => 'Financial profile details',
            'subtitle'  => '',
            'table_head_text1' => 'Financial Profile - View/Edit'
        ];
        $sidebar_link = 'admin-settings-financial_profiles-view_edit';

        return view('admin/settings/financial_profile_edit', [
            'breadcrumbs' => $breadcrumbs,
            'text_parts'  => $text_parts,
            'in_sidebar'  => $sidebar_link,
            'profile'     => $profile,
            'countries'   => $countries
        ]);
    }

    public function update_shop_financial_profile(Request $request, $id){
        $user = Auth::user();
        if (!$user || !$user->is_back_user()) {
            //return redirect()->intended(route('admin/login'));
            return [
                'success' => false,
                'errors'  => 'Error while trying to authenticate. Login first then use this function.',
                'title'   => 'Not logged in'];
        }
        elseif (!$user->can('create-financial-profile')){
            return [
                'success'   => false,
                'errors'    => 'You don\'t have permission to access this page',
                'title'     => 'Permission Error'];
            //return redirect()->intended(route('admin/error/permission_denied'));
        }

        $vars = $request->only('profile_name', 'company_name', 'bank_name', 'bank_account', 'organisation_number', 'address1', 'address2', 'city', 'postal_code', 'region', 'country');

        $finance_profile = FinancialProfile::where('id','=',$id)->get()->first();

        $fillable = [
            'profile_name'  => $vars['profile_name'],
            'company_name'  => $vars['company_name'],
            'bank_name'     => $vars['bank_name'],
            'bank_account'  => $vars['bank_account'],
            'organisation_number'   => $vars['organisation_number'],
            'address1'      => $vars['address1'],
            'address2'      => $vars['address2'],
            'city'          => $vars['city'],
            'postal_code'   => $vars['postal_code'],
            'region'        => $vars['region'],
            'country_id'    => $vars['country'],
        ];
        $validator = Validator::make($fillable, FinancialProfile::rules('PUT', $finance_profile->id), FinancialProfile::$message, FinancialProfile::$attributeNames);
        if ($validator->fails()){
            return array(
                'success' => false,
                'title'  => 'Error validating input information',
                'errors' => $validator->getMessageBag()->toArray()
            );
        }

        try {
            $finance_profile->update($fillable);

            Activity::log([
                'contentId'     => $user->id,
                'contentType'   => 'financial_profile',
                'action'        => 'Financial Profile Update',
                'description'   => 'Financial profile successfully updated : '.$finance_profile->id,
                'details'       => 'Updated by user : '.$user->id,
                'updated'       => true,
            ]);

            return [
                'success' => true,
                'message' => 'Financial profile updated. This will have influence in the future',
                'title'   => 'Financial Profile Updated'
            ];
        }
        catch (Exception $e) {
            return Response::json(['error' => 'Booking Error'], Response::HTTP_CONFLICT);
        }
    }

}
