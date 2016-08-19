<?php

namespace App\Http\Controllers;

use App\MembershipPlan;
use App\MembershipPlanPrice;
use App\MembershipRestriction;
use App\MembershipRestrictionType;
use App\ShopResourceCategory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Auth;
use App\Http\Requests;

use Regulus\ActivityLog\Models\Activity;
use Validator;
use App\ShopLocations;
use App\CashTerminal;

class MembershipPlansController extends Controller
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

        return view('admin/membership_plans/all_plans', [
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

        return view('admin/membership_plans/add_plan', [
            'breadcrumbs' => $breadcrumbs,
            'text_parts'  => $text_parts,
            'in_sidebar'  => $sidebar_link,
            'cash_terminals' => $cash_terminals,
            'shops' => $shops,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!Auth::check()) {
            //return redirect()->intended(route('admin/login'));
            return [
                'success' => false,
                'errors' => 'Error while trying to authenticate. Login first then use this function.',
                'title' => 'Not logged in'];
        }
        else{
            $user = Auth::user();
        }

        $vars = $request->only('name', 'price', 'plan_period', 'binding_period', 'administration_fee_name', 'administration_fee_amount', 'plan_calendar_color', 'membership_short_description', 'membership_long_description');

        if (!in_array($vars['plan_period'], ['7','14','30','90','180','360'])){
            return [
                'success' => false,
                'errors'  => 'Error validating time for invoicing period',
                'title'   => 'Error Invoicing Period'];
        }

        $fillable = [
            'name' => $vars['name'],
            'plan_calendar_color' => $vars['plan_calendar_color'],
            'status' => 'pending',
            'price_id' => -1,
            'plan_period' => $vars['plan_period'],
            'binding_period' => $vars['binding_period'],
            'administration_fee_name' => $vars['administration_fee_name'],
            'administration_fee_amount' => $vars['administration_fee_amount'],
            'short_description' => $vars['membership_short_description'],
            'description' => $vars['membership_long_description']
        ];
        $validator = Validator::make($fillable, MembershipPlan::rules('POST'), MembershipPlan::$message, MembershipPlan::$attributeNames);
        if ($validator->fails()){
            return array(
                'success' => false,
                'title'  => 'Error validating input information',
                'errors' => $validator->getMessageBag()->toArray()
            );
        }

        try {
            $the_plan = MembershipPlan::create($fillable);

            $fillable = [
                'membership_id' => $the_plan->id,
                'price'         => $vars['price'],
                'discount'      => 0
            ];

            $validator = Validator::make($fillable, MembershipPlanPrice::rules('POST'), MembershipPlanPrice::$message, MembershipPlanPrice::$attributeNames);
            if ($validator->fails()){
                //$with_warnings = true;
            }
            else{
                $the_price = MembershipPlanPrice::create($fillable);
                $the_plan->price_id = $the_price->id;
                $the_plan->save();
            }

            Activity::log([
                'contentId'     => $user->id,
                'contentType'   => 'membership_plan',
                'action'        => 'New Membership Plan',
                'description'   => 'New membership plan created : '.$the_plan->id,
                'details'       => 'Created by user : '.$user->id,
                'updated'       => false,
            ]);

            return [
                'success' => true,
                'message' => 'Page will reload so you can add all the other details for this plan...',
                'title'   => 'Membership Plan Created',
                'redirect_link' => route('admin.membership_plan.edit',['id'=>$the_plan->id])
            ];
        }
        catch (Exception $e) {
            return Response::json(['error' => 'Booking Error'], Response::HTTP_CONFLICT);
        }
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

        return view('admin/membership_plans/view_plan', [
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

        return view('admin/membership_plans/edit_plan', [
            'breadcrumbs'   => $breadcrumbs,
            'text_parts'    => $text_parts,
            'in_sidebar'    => $sidebar_link,
            'membership_plan'   => $the_plan,
            'activities'    => $activities,
            'restrictions'  => $restrictions
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (!Auth::check()) {
            //return redirect()->intended(route('admin/login'));
            return ['success' => false, 'errors' => 'Error while trying to authenticate. Login first then use this function.', 'title' => 'Not logged in'];
        }
        else{
            $user = Auth::user();
        }

        $the_plan = MembershipPlan::with('price')->where('id', '=', $id)->get()->first();
        if (!$the_plan){
            return [
                'success' => false,
                'errors'  => 'Membership plan not found in the database',
                'title'   => 'Error updating membership'
            ];
        }

        $vars = $request->only('name','price','plan_period','binding_period','administration_fee_name','administration_fee_amount','plan_calendar_color','membership_short_description','membership_long_description','status');

        if (!in_array($vars['plan_period'], [7,14,30,90,180,360])){
            return [
                'success' => false,
                'errors'  => 'Plan period not in the correct form. Try again!',
                'title'   => 'Error updating membership'
            ];
        }

        $fillable = [
            'name' => $vars['name'],
            'plan_calendar_color' => $vars['plan_calendar_color'],
            'price_id'  => $the_plan->price_id,
            'status' => $vars['status'],
            'plan_period' => $vars['plan_period'],
            'binding_period' => $vars['binding_period'],
            'administration_fee_name' => $vars['administration_fee_name'],
            'administration_fee_amount' => $vars['administration_fee_amount'],
            'short_description' => $vars['membership_short_description'],
            'description' => $vars['membership_long_description']
        ];
        $validator = Validator::make($fillable, MembershipPlan::rules('PATCH', $the_plan->id), MembershipPlan::$message, MembershipPlan::$attributeNames);
        if ($validator->fails()){
            return array(
                'success' => false,
                'title'  => 'Error validating input information',
                'errors' => $validator->getMessageBag()->toArray()
            );
        }
        else{
            $the_plan->update($fillable);
        }

        if ($vars['price'] != $the_plan->price[0]->price) {
            $fillable = [
                'membership_id' => $the_plan->id,
                'price' => $vars['price'],
                'discount' => 0
            ];

            $validator = Validator::make($fillable, MembershipPlanPrice::rules('POST'), MembershipPlanPrice::$message, MembershipPlanPrice::$attributeNames);
            if ($validator->fails()) {
                //$with_warnings = true;
            }
            else {
                $the_price = MembershipPlanPrice::create($fillable);
                $the_plan->price_id = $the_price->id;
                $the_plan->save();
            }
        }

        Activity::log([
            'contentId'     => $user->id,
            'contentType'   => 'membership_plan',
            'action'        => 'Membership Plan Update',
            'description'   => 'Membership Plan Updated, plan ID : '.$the_plan->id,
            'details'       => 'Updated by user : '.$user->id,
            'updated'       => false,
        ]);

        return [
            'success' => true,
            'message' => 'Page will reload so you can add all the other details for this plan...',
            'title'   => 'Membership Plan Created',
            'redirect_link' => route('admin.membership_plan.edit',['id'=>$the_plan->id])
        ];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function add_plan_restriction(Request $request){
        $user = Auth::user();
        if (!$user || !$user->is_back_user()) {
            return redirect()->intended(route('admin/login'));
        }

        $vars = $request->only('type','activities','membership_id','min_val','max_val','hour_start','hour_stop','minute_start',
            'minute_stop','day_selection','open_bookings','cancellation_before_hours', 'special_current_day', 'special_days_ahead');

        $the_plan = MembershipPlan::where('id','=',$vars['membership_id'])->get()->first();
        $restriction = MembershipRestrictionType::where('name', '=', $vars["type"])->get()->first();

        if ($the_plan && $restriction){
            $fillable = [
                'membership_id'     => $the_plan->id,
                'restriction_id'    => $restriction->id,
                'value'             => '0',
                'min_value'         => '0',
                'max_value'         => '0',
                'time_start'        => '00:00',
                'time_end'          => '00:00',
                'special_permissions'   => ''
            ];

            switch($vars["type"]){
                case 'included_activity' : {
                    $selected_activity = [];
                    if (is_array($vars['activities'][0])) {
                        $input_activities = $vars['activities'][0];
                    } else {
                        $input_activities = $vars['activities'];
                    }

                    foreach ($input_activities as $activity) {
                        $verify_activity = ShopResourceCategory::where('id', '=', $activity)->get()->first();
                        if ($verify_activity) {
                            $selected_activity[] = $verify_activity->id;
                        }
                    }
                    $fillable['value'] = json_encode($selected_activity);
                }
                break;
                case 'time_of_day' : {
                    $selected_days = [];
                    if (is_array($vars['day_selection'][0])) {
                        $input_days = $vars['day_selection'][0];
                    } else {
                        $input_days = $vars['day_selection'];
                    }

                    foreach ($input_days as $day) {
                        if (in_array($day, [0, 1, 2, 3, 4, 5, 6])) {
                            $selected_days[] = (int)$day;
                        }
                    }
                    $fillable['value'] = json_encode($selected_days);

                    $fillable['time_start'] = $vars['hour_start'] . ':' . $vars['minute_start'];
                    $fillable['time_end'] = $vars['hour_stop'] . ':' . $vars['minute_stop'];

                    if ($vars['special_current_day']!='' || $vars['special_days_ahead']!=''){
                        $fillable['special_permissions'] = json_encode([
                            'special_current_day' => $vars['special_current_day'],
                            'special_days_ahead' => $vars['special_days_ahead']
                        ]);
                    }
                }
                break;
                case 'open_bookings' : {
                    $fillable['value'] = $vars['open_bookings'];
                }
                break;
                case 'cancellation' : {
                    $fillable['value'] = $vars['cancellation_before_hours'];
                }
                break;
                case 'price' : {
                    $fillable['value'] = '';
                }
                break;
                case 'booking_time_interval' : {
                    $fillable['min_value'] = $vars['min_val'];
                    $fillable['max_value'] = $vars['max_val'];
                }
                break;
                default:
                    return[];
                break;
            }

            $validator = Validator::make($fillable, MembershipRestriction::rules('POST', $the_plan->id), MembershipRestriction::$message, MembershipRestriction::$attributeNames);
            if ($validator->fails()){
                return array(
                    'success' => false,
                    'title'  => 'Error validating input information',
                    'errors' => $validator->getMessageBag()->toArray()
                );
            }
            else{
                $restriction = MembershipRestriction::create($fillable);
                return [
                    'success' => true,
                    'message' => 'The selected restriction/property was added successfully to the selected plan.',
                    'title'   => ''
                ];
            }
        }
    }

    public function remove_plan_restriction(Request $request){
        if (!Auth::check()) {
            return [
                'success' => false,
                'title'  => 'You have to be logged in',
                'errors' => 'Please login to use this function'
            ];
        }
        else{
            $user = Auth::user();
        }

        $vars = $request->only('membership_id','restriction_id');

        $the_plan = MembershipPlan::where('id','=',$vars['membership_id'])->get()->first();
        if (!$the_plan){
            return [
                'success' => false,
                'title'  => 'Membership plan error',
                'errors' => 'Membership plan not found in the system!'
            ];
        }

        $restriction = MembershipRestriction::where('id', '=', $vars['restriction_id'])->where('membership_id','=',$the_plan->id)->get()->first();
        if ($the_plan && $restriction){
            $restriction->delete();
            return [
                'success' => true,
                'title'   => 'Restriction removed',
                'message' => 'Restriction removed from the selected plan.',
            ];
        }
        else{
            return [
                'success' => false,
                'title'  => 'Restriction not found',
                'errors' => 'The restriction was not found for the selected membership plan'
            ];
        }
    }

    public function ajax_get_plan_details(Request $request, $status='active'){
        $vars = $request->only('selected_plan');

        $the_plan = MembershipPlan::with('price')->where('id','=',$vars['selected_plan'])->where('status','=',$status)->get()->first();
        if ($the_plan){
            $details = [
                'success'   => true,
                'name'      => $the_plan->name,
                'price'     => $the_plan->price[0]->price.' NOK',
                'invoice_time'  => $the_plan->plan_period,
                'one_time_fee_name' => $the_plan->administration_fee_name,
                'one_time_fee_value'=> $the_plan->administration_fee_amount.' NOK',
                'description'   => $the_plan->short_description,
                'plan_order_id' => $the_plan->id
            ];
            switch ($the_plan->plan_period){
                case 7 :   $details['invoice_time'] = 'once every 7 days';
                    break;
                case 14 :  $details['invoice_time'] = 'once every 14 days';
                    break;
                case 30 :  $details['invoice_time'] = 'one per month';
                    break;
                case 90 :  $details['invoice_time'] = 'once every three months';
                    break;
                case 180 : $details['invoice_time'] = 'once every six months';
                    break;
                case 360 : $details['invoice_time'] = 'once per year';
                    break;
            }

            return $details;
        }
        else{
            return [
                'success'   => false,
                'errors'    => 'The requested Membership Plan was not found',
                'title'     => 'Error getting Plan Details'
            ];
        }
    }
}
