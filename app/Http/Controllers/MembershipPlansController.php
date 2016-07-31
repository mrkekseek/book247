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
        if (!Auth::check()) {
            return redirect()->intended(route('admin/login'));
        }
        else{
            $user = Auth::user();
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
        if (!Auth::check()) {
            return redirect()->intended(route('admin/login'));
        }
        else{
            $user = Auth::user();
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

        $vars = $request->only('name', 'price', 'plan_period', 'administration_fee_name', 'administration_fee_amount', 'plan_calendar_color', 'membership_short_description', 'membership_long_description');

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
                'redirect_link' => route('membership_plan.edit',['id'=>$the_plan->id])
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!Auth::check()) {
            return redirect()->intended(route('admin/login'));
        }
        else{
            $user = Auth::user();
        }

        $the_plan = MembershipPlan::with('price')->with('membership_restrictions')->where('id','=',$id)->get()->first();
        if (!$the_plan){
            $the_plan = false;
        }

        $activities = ShopResourceCategory::all()->sortBy('name');
        $restrictions = array();
        $plan_restrictions = MembershipRestriction::with('restriction_title')->where('membership_id','=',$the_plan->id)->orderBy('restriction_id','asc')->get();
        foreach($plan_restrictions as $restriction){
            switch($restriction->restriction_title->name){
                case 'time_of_day' :
                    $days_in = '';
                    $days = json_decode($restriction->value);
                    foreach ($days as $day){
                        $days_in[] = jddayofweek($day, 1);
                    }
                    $description = 'Included days : <b>'.implode(', ',$days_in).'</b><br />between <b>'.$restriction->time_start.' - '.$restriction->time_end.'</b>';
                    $color = 'note-info';
                break;
                case 'open_bookings' :
                    $description = 'Number of active open bookings included in membership plan : <b>'.$restriction->value.'</b>';
                    $color = 'note-success';
                break;
                case 'cancellation' :
                    $description = 'Number of hours before booking start until cancellation is possible : <b>'.$restriction->value.' hours</b>';
                    $color = 'note-warning';
                break;
                case 'price' :
                    $description = '';
                    $color = 'note-success';
                break;
                case 'included_activity' :
                    $in_activities = ShopResourceCategory::whereIn('id', json_decode($restriction->value))->get();
                    $available = array();
                    foreach($in_activities as $activity){
                        $available[] = $activity->name;
                    }

                    $description = 'Following activities are included : <b>'.implode(', ', $available).'</b>';
                    $color = 'note-success';
                break;
                case 'booking_time_interval' :
                    $description = 'Booking can be made for intervals between <b>'.$restriction->min_value.' hours</b> from now until <b>'.$restriction->max_value.' hours</b> from now.';
                    $color = 'note-info';
                break;
            }

            $restrictions[] = [
                'id'            => $restriction->id,
                'title'         => $restriction->restriction_title->title,
                'description'   => $description,
                'color'         => $color
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

        $vars = $request->only('name','price','plan_period','administration_fee_name','administration_fee_amount','plan_calendar_color','membership_short_description','membership_long_description','status');

        if (!in_array($vars['plan_period'], ['7','14','30','90','180','360'])){
            return 'error';
        }

        $fillable = [
            'name' => $vars['name'],
            'plan_calendar_color' => $vars['plan_calendar_color'],
            'price_id'  => $the_plan->price_id,
            'status' => $vars['status'],
            'plan_period' => $vars['plan_period'],
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
            'redirect_link' => route('membership_plan.edit',['id'=>$the_plan->id])
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
        $vars = $request->only('type','activities','membership_id','min_val','max_val','hour_start','hour_stop','minute_start','minute_stop','day_selection','open_bookings','cancellation_before_hours');

        $the_plan = MembershipPlan::where('id','=',$vars['membership_id'])->get()->first();
        $restriction = MembershipRestrictionType::where('name', '=', $vars["type"])->get()->first();

        if ($the_plan && $restriction){
            $fillable = [
                'membership_id'     => $the_plan->id,
                'restriction_id'  => $restriction->id,
                'value'             => '0',
                'min_value'         => '0',
                'max_value'         => '0',
                'time_start'        => '00:00',
                'time_end'          => '00:00'
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
}
