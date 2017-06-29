<?php

namespace App\Http\Controllers\Federation;

use App\Http\Controllers\MembershipPlansController as Base;
use App\MembershipPlan;
use App\MembershipPlanPrice;
use App\MembershipRestriction;
use App\MembershipRestrictionType;
use App\ShopResourceCategory;
use App\UserMembership;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Auth;
use App\Http\Requests;

use Illuminate\Support\Facades\Cache;
use Regulus\ActivityLog\Models\Activity;
use Validator;
use App\ShopLocations;
use App\CashTerminal;
use Illuminate\Support\Facades\Config;

/*
 * This controller is linked to the Membership Plans and the management of the membership plans
 */
class MembershipPlansController extends Base
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        if (!$user || !$user->is_back_user()) {
            return redirect()->intended(route('admin/login'));
        }

        $all_plans = [];
        $membership_plans = MembershipPlan::orderBy('name', 'ASC')->get();
        if ($membership_plans){
            $i = 1;
            foreach($membership_plans as $the_plan){
                $the_price = $the_plan->get_price();

                $all_plans[$i] = [
                    'id'    => $the_plan->id,
                    'color' => $the_plan->plan_calendar_color,
                    'name'  => $the_plan->name,
                    'plan_period'   => $the_plan->plan_period,
                    'admin_fee'     => $the_plan->administration_fee_amount,
                    'active_members'=> 999,
                    'price' => @$the_price->price,
                    'status' => $the_plan->status
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
            'title'     => 'All Cash Terminals',
            'subtitle'  => 'add/edit/view terminals',
            'table_head_text1' => 'Backend Roles Permissions List'
        ];
        $sidebar_link= 'admin-backend-memberships-all_plans';

        return view('admin/membership_plans/federation/all_plans', [
            'breadcrumbs' => $breadcrumbs,
            'text_parts'  => $text_parts,
            'in_sidebar'  => $sidebar_link,
            'all_plans'   => $all_plans
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = Auth::user();
        if (!$user || !$user->is_back_user()) {
            return redirect()->intended(route('admin/login'));
        }
        elseif (!$user->can('create-membership-plans')){
            return redirect()->intended(route('admin/error/permission_denied'));
        }

        $cash_terminals = CashTerminal::with('shopLocation')->get();
        $shops = ShopLocations::all();

        $breadcrumbs = [
            'Home'              => route('admin'),
            'Administration'    => route('admin'),
            'Back End User'     => route('admin'),
            'Permissions'        => '',
        ];
        $text_parts  = [
            'title'     => 'Add new membership plan',
            'subtitle'  => '',
            'table_head_text1' => 'Membership Plans - Create New'
        ];
        $sidebar_link = 'admin-backend-memberships-new_plans';

        return view('admin/membership_plans/federation/add_plan', [
            'breadcrumbs' => $breadcrumbs,
            'text_parts'  => $text_parts,
            'in_sidebar'  => $sidebar_link,
            'cash_terminals' => $cash_terminals,
            'shops' => $shops,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = Auth::user();
        if (!$user || !$user->is_back_user()) {
            return redirect()->intended(route('admin/login'));
        }

        $the_plan = MembershipPlan::with('price')->with('restrictions')->where('id','=',$id)->get()->first();
        if (!$the_plan){
            $the_plan = false;
        }

        $activities = ShopResourceCategory::all()->sortBy('name');
        $restrictions = array();
        $plan_restrictions = MembershipRestriction::with('restriction_title')->where('membership_id','=',$the_plan->id)->orderBy('restriction_id','asc')->get();
        foreach($plan_restrictions as $restriction){
            $formatted = $restriction->format_for_display_boxes();

            $restrictions[] = [
                'id'            => $restriction->id,
                'title'         => $restriction->restriction_title->title,
                'description'   => $formatted['description'],
                'color'         => $formatted['color']
            ];
        }

        $breadcrumbs = [
            'Home'              => route('admin'),
            'Administration'    => route('admin'),
            'Back End User'     => route('admin'),
            'Permissions'        => '',
        ];
        $text_parts  = [
            'title'     => 'Edit Plane - Full month free',
            'subtitle'  => '',
            'table_head_text1' => 'Backend Roles Permissions List'
        ];
        $sidebar_link= 'admin-backend-memberships-all_plans';

        return view('admin/membership_plans/federation/view_plan', [
            'breadcrumbs'   => $breadcrumbs,
            'text_parts'    => $text_parts,
            'in_sidebar'    => $sidebar_link,
            'membership_plan'   => $the_plan,
            'activities'    => $activities,
            'restrictions'  => $restrictions
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = Auth::user();
        if (!$user || !$user->is_back_user()) {
            return redirect()->intended(route('admin/login'));
        }
        elseif (!$user->can('manage-membership-plans')){
            /*return [
                'success'   => false,
                'errors'    => 'You don\'t have permission to access this page',
                'title'     => 'Permission Error'];*/
            return redirect()->intended(route('admin/error/permission_denied'));
        }

        $the_plan = MembershipPlan::with('price')->with('restrictions')->where('id','=',$id)->get()->first();
        if (!$the_plan){
            $the_plan = false;
        }

        $activities = ShopResourceCategory::all()->sortBy('name');
        $restrictions = array();
        $plan_restrictions = MembershipRestriction::with('restriction_title')->where('membership_id','=',$the_plan->id)->orderBy('restriction_id','asc')->get();
        foreach($plan_restrictions as $restriction){
            $formatted = $restriction->format_for_display_boxes();

            $restrictions[] = [
                'id'            => $restriction->id,
                'title'         => $restriction->restriction_title->title,
                'description'   => $formatted['description'],
                'color'         => $formatted['color']
            ];
        }

        $breadcrumbs = [
            'Home'              => route('admin'),
            'Administration'    => route('admin'),
            'Back End User'     => route('admin'),
            'Permissions'        => '',
        ];
        $text_parts  = [
            'title'     => 'Edit Membership - '.$the_plan->name,
            'subtitle'  => '',
            'table_head_text1' => 'Backend Roles Permissions List'
        ];
        $sidebar_link= 'admin-backend-memberships-all_plans';

        return view('admin/membership_plans/federation/edit_plan', [
            'breadcrumbs'   => $breadcrumbs,
            'text_parts'    => $text_parts,
            'in_sidebar'    => $sidebar_link,
            'membership_plan'   => $the_plan,
            'activities'    => $activities,
            'restrictions'  => $restrictions
        ]);
    }
}
