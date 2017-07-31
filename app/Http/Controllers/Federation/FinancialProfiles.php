<?php
namespace App\Http\Controllers\Federation;


use App\FinancialProfile;
use Validator;
use Illuminate\Http\Request;
use Auth;
use App\Http\Requests;
use Regulus\ActivityLog\Models\Activity;
use Webpatser\Countries\Countries;
use App\Http\Controllers\FinancialProfiles as Base;

class FinancialProfiles extends Base
{

    public function list_all(){
        $user = Auth::user();
        if ( ! $user || !$user->is_back_user())
        {
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

        return view('admin/settings/federation/financial_profiles_list_all', [
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

        return view('admin/settings/federation/financial_profiles_add_new', [
            'breadcrumbs' => $breadcrumbs,
            'text_parts'  => $text_parts,
            'in_sidebar'  => $sidebar_link,
            'cash_terminals' => [],
            'shops' => [],
            'countries'   => $countries
        ]);
    }

    public function show_shop_financial_profile($id){
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

        return view('admin/settings/federation/financial_profile_view', [
            'breadcrumbs' => $breadcrumbs,
            'text_parts'  => $text_parts,
            'in_sidebar'  => $sidebar_link,
            'profile'     => $profile,
            'countries'   => $countries
        ]);
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

        return view('admin/settings/federation/financial_profile_edit', [
            'breadcrumbs' => $breadcrumbs,
            'text_parts'  => $text_parts,
            'in_sidebar'  => $sidebar_link,
            'profile'     => $profile,
            'countries'   => $countries
        ]);
    }
}