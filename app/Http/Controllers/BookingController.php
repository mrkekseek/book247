<?php

namespace App\Http\Controllers;

use App\Booking;
use App\BookingFinancialTransaction;
use App\BookingInvoice;
use App\BookingInvoiceItem;
use App\Invoice;
use App\InvoiceItem;
use App\MembershipPlan;
use App\ShopLocations;
use App\ShopResource;
use App\ShopResourceCategory;
use App\UserMembership;
use App\UserSettings;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;
use App\Role;
use App\VatRate;
use Validator;
use Zizaco\Entrust\EntrustRole;
use Auth;
use Carbon\Carbon;
use DateInterval;
use DatePeriod;
use DB;
use Regulus\ActivityLog\Models\Activity;
use Snowfire\Beautymail\Beautymail;
use Cache;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     * Used on front end for user bookings on homepage
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!Auth::check()) {
            //return redirect()->intended(route('admin/login'));
            return 'error_authentication';
        }
        else{
            $user = Auth::user();
        }

        $vars = $request->only('selected_activity', 'selected_date', 'selected_location', 'selected_payment', 'selected_resource', 'selected_time', 'book_key', 'player');
        $search_key = Booking::new_search_key();

        if ($vars['selected_location']==-1){
            // the user selected all locations from top so we need to check what location he selected
            $resource = ShopResource::where('id','=',$vars['selected_resource'])->get()->first();
            if ($resource){
                $vars['selected_location'] = $resource->location_id;
            }
            else{
                return 'error';
            }
        }

        $booking_start_time = trim($vars['selected_time']);
        $booking_end_time   = Carbon::createFromFormat('G:i',trim($vars['selected_time']))->addMinutes(30)->format('G:i');

        $fillable = [
            'by_user_id'    => $user->id,
            'for_user_id'   => isset($vars['player'])?$vars['player']:$user->id,
            'location_id'   => $vars['selected_location'],
            'resource_id'   => $vars['selected_resource'],
            'status'        => 'pending',
            'date_of_booking'   => $vars['selected_date'],
            'booking_time_start'    => $booking_start_time,
            'booking_time_stop'     => $booking_end_time,
            'payment_type'  => 'membership',
            'payment_amount'  => 0,
            'membership_id' => 1,
            'invoice_id'    => -1,
            'search_key'    => $search_key,
        ];

        $canBook = BookingController::validate_booking($fillable, $vars['book_key']);
        if ($canBook['status']==false){
            return ['booking_key' => ''];
        }
        else{
            $fillable['payment_type'] = $canBook['payment'];

            $book_price = ShopResource::find($fillable['resource_id']);
            if ($book_price){
                $fillable['payment_amount'] = $book_price->get_price($vars['selected_date'], $booking_start_time);
            }
            else{
                return ['booking_key' => ''];
            }

            if ($fillable['payment_type']=="membership"){
                $membershipID = UserMembership::where('user_id','=',$fillable['for_user_id'])->where('status','=','active')->get()->first();
                if ($membershipID){
                    $fillable['membership_id'] = $membershipID->id;
                }
            }
        }

        $validator = Validator::make($fillable, Booking::rules('POST'), Booking::$message, Booking::$attributeNames);
        if ($validator->fails()){
            return array(
                'success' => false,
                'errors' => $validator->getMessageBag()->toArray()
            );
        }

        try {
            if ($vars['book_key']==""){
                $the_booking = Booking::create($fillable);

                Activity::log([
                    'contentId'     => $user->id,
                    'contentType'   => 'bookings',
                    'action'        => 'New Booking',
                    'description'   => 'New booking created : '.$the_booking->id,
                    'details'       => 'User Email : '.$user->email,
                    'updated'       => false,
                ]);
            }
            else{
                $the_booking = Booking::where('search_key', '=', $vars['book_key'])->get()->first();
                if ($the_booking) {
                    $fillable['search_key'] = $vars['book_key'];
                    $the_booking->fill($fillable);
                    $the_booking->save();

                    Activity::log([
                        'contentId'     => $user->id,
                        'contentType'   => 'bookings',
                        'action'        => 'New Booking',
                        'description'   => 'New booking created : '.$the_booking->id,
                        'details'       => 'User Email : '.$user->email,
                        'updated'       => false,
                    ]);
                }
                else{
                    //$search_key = $vars['book_key'];
                }
            }

            return [
                'booking_key'   => $the_booking->search_key,
                'booking_type'  => $the_booking->payment_type,
                'booking_price' => $the_booking->payment_amount];
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
        //
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
        //
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

    public function confirm_booking(Request $request){
        //xdebug_var_dump($request);
    }

    /*
     * Cancel an active or pending booking
     */
    public function cancel_booking(Request $request){
        if (!Auth::check()) {
            //return redirect()->intended(route('admin/login'));
            return [
                'success' => false,
                'title'   => 'Authentication Error',
                'errors'  => 'Please reload the page and try again'
            ];
        }
        else{
            $user = Auth::user();
        }

        $vars = $request->only('search_key');
        if ($user->can('booking-change-update')){
            $booking = Booking::where('search_key','=',$vars['search_key'])->get()->first();
        }
        else {
            $booking = Booking::where('search_key','=',$vars['search_key'])
                        ->where(function($query) use ($user){
                                    $query->where('for_user_id','=',$user->id)->orWhere('by_user_id','=',$user->id);
                       })->get()->first();
        }

        if ($booking){
            //if (in_array($booking->status,['active','pending'])){
            if ($this->can_cancel_booking($booking->id)){
                $old_status = $booking->status;
                if ( $booking->cancel_booking() ){
                    if ($old_status=='active'){
                        // send_email_to_user
                        $player = User::where('id','=',$booking->for_user_id)->get()->first();
                        $booking_details = $booking->get_summary_details(false);

                        $beautymail = app()->make(Beautymail::class);
                        $beautymail->send('emails.booking.cancel_booking', ['player'=>$player, 'booking'=>$booking_details, 'logo' => ['path' => 'http://sqf.se/wp-content/uploads/2012/12/sqf-logo.png']], function($message) use ($player, $booking_details)
                        {
                            $message
                                ->from('bogdan@bestintest.eu')
                                ->to($player->email, $player->first_name.' '.$player->middle_name.' '.$player->last_name)
                                //->to('stefan.bogdan@ymail.com', $player->first_name.' '.$player->middle_name.' '.$player->last_name)
                                ->subject('Booking System - Your booking for '.$booking_details["bookingDate"].' was canceled');
                        });
                    }

                    Activity::log([
                        'contentId'     => $user->id,
                        'contentType'   => 'bookings',
                        'action'        => 'Cancel Booking',
                        'description'   => 'Booking cancelled with the ID : '.$booking->id,
                        'details'       => 'User Email : '.$user->email,
                        'updated'       => true,
                    ]);
                    return [
                        'success' => true,
                        'title'   => 'Booking canceled',
                        'message' => 'Booking canceled and invoice updated accordingly [if it was a paid booking]'
                    ];
                }
                else{
                    return [
                        'success' => false,
                        'title'   => 'Booking canceled',
                        'errors' => 'Booking canceled and invoice updated accordingly [if it was a paid booking]'
                    ];
                }
            }
            else{
                return [
                    'success' => false,
                    'title'   => 'Action error',
                    'errors'  => 'The selected booking can\'t be canceled. Please check your membership plan rules regarding cancellation or ask one of our staff about this.'
                ];
            }
        }
        else{
            return [
                'success' => false,
                'title'   => 'Booking not found',
                'errors'  => 'Please make the booking process again. Remember you have 60 seconds to complete the booking before it expires.'
            ];
        }
    }

    /**
     * Change the player for a selected booking based on search_key
     * @param Request $request - [search_key,player]
     * @return array
     */
    public function change_booking_player(Request $request){
        if (!Auth::check()) {
            //return redirect()->intended(route('admin/login'));
            return ['error' => 'Authentication Error'];
        }
        else{
            $user = Auth::user();
        }

        $vars = $request->only('search_key', 'player');
        $booking = Booking::where('search_key','=',$vars['search_key'])->get()->first();
        if ($booking){
            if (FrontEndUserController::are_friends($vars['player'], $booking->by_user_id) || $booking->by_user_id==$vars['player']) {
                Booking::whereIn('status', ['active', 'pending'])->where('search_key', '=', $vars['search_key'])->update(['for_user_id' => $vars['player']]);

                Activity::log([
                    'contentId'     => $user->id,
                    'contentType'   => 'bookings',
                    'action'        => 'Change Booking Player',
                    'description'   => 'booking player changed to : '.$booking->for_user_id,
                    'details'       => 'User Email : '.$user->email,
                    'updated'       => true,
                ]);

                return ['success' => 'true', 'message' => 'All is good.'];
            }
            else{
                return ['error' => 'No bookings found to confirm. Please make the booking process again. Remember you have 60 seconds to complete the booking before it expires.'];
            }
        }
        else{
            return ['error' => 'No bookings found to confirm. Please make the booking process again. Remember you have 60 seconds to complete the booking before it expires.'];
        }
    }

    /**
     * Check if the booking fillable can be transformed into a booking
     * @param $fillable
     * @param string $search_key
     * @param bool $recurring
     * @return array
     */
    private function validate_booking($fillable, $search_key='', $recurring = false, $is_employee = false){
        if (!Auth::check()) {
            $message['status'] = false;
            return $message;
        }
        else{
            $user = Auth::user();
        }

        $message = ['status'=>true, 'payment'=>'membership'];
        usleep(1000);

        if ($recurring==true){
            $free_open_bookings = 999;
            $message['payment'] = 'recurring';
        }
        elseif ($is_employee==true && $user->id != $fillable['for_user_id']){
            $free_open_bookings = 999;
            $message['payment'] = 'cash';
        }
        else{
            $message['payment'] = 'membership';

            $for_user_id = User::where('id','=',$fillable['for_user_id'])->get()->first();
            if ($for_user_id){
                // get user membership if exists

                /*$active_membership = UserMembership::where('user_id','=',$for_user_id->id)->where('status','=','active')->get()->first();
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
                }*/

                $restrictions = $for_user_id->get_membership_restrictions();
                $message['payment'] = $this->check_membership_restrictions($for_user_id->id, $fillable, $restrictions, $search_key);
            }
            else{
                $free_open_bookings = 0;
                $message['payment'] = 'cash';
            }
        }

        // check for open bookings
        //$ownBookings = Booking::whereIn('status',['pending','active'])
        //    ->where('for_user_id','=',$fillable['for_user_id'])
        //    ->where('search_key','!=',$search_key)
        //    ->get();

        if ($recurring==true){
            // is a recurrent booking
        }
        else if($is_employee==true && $user->id != $fillable['for_user_id']){
            // is a booking created by an employee in the backend
        }
        //else if ( $free_open_bookings <= sizeof($ownBookings) ){
            // no open bookings except the search key
            //$message['payment'] = 'cash';
        //}

        // check for existing booking on the same resource, bookings that are already made
        $openBookings = Booking::whereIn('status',['pending','active'])
            ->where('resource_id','=',$fillable['resource_id'])
            ->where('location_id','=',$fillable['location_id'])
            ->where('date_of_booking','=',$fillable['date_of_booking'])
            ->where('booking_time_start','=',$fillable['booking_time_start'])
            ->where('search_key','!=',$search_key)
            ->get();
        if (sizeof($openBookings)>0){
            // we have another booking with the same details
            $message['status'] = false;
        }

        return $message;
    }

    private function check_membership_restrictions($member, $fillable, $restrictions, $search_key = 'c12abab@abab#abab12c'){
        // we assume the payment type is membership then we go through all the restrictions and see if any of them is broken
        $payment_type = 'membership';

        // get activity selected for booking
        $resource_category = ShopResource::select('category_id')->where('id','=',$fillable['resource_id'])->get()->first();

        // check for open bookings
        $ownBookings = Booking::whereIn('status',['pending','active'])
            ->where('for_user_id','=',$fillable['for_user_id'])
            ->where('search_key','!=',$search_key)
            ->where('payment_type','=','membership')
            ->get();
        $nr_of_open_bookings = sizeof($ownBookings);

        $time_of_day_result = [];

        foreach ($restrictions as $restriction){
            switch ($restriction['name']){
                case 'time_of_day' : {
                    $status = true; //break;

                    //'value' => string '[1,2,3,4,5]' (length=11)
                    $selected_days  = json_decode($restriction['value']);
                    //'time_start' => string '06:00' (length=5)
                    if (substr_count($restriction['time_start'], ':')==1) {
                        $hour_start = Carbon::createFromFormat('H:i', $restriction['time_start']);
                    }
                    else{
                        $hour_start = Carbon::createFromFormat('H:i:s', $restriction['time_start']);
                    }
                    //'time_end' => string '14:00' (length=5)
                    if (substr_count($restriction['time_end'], ':')==1) {
                        $hour_end = Carbon::createFromFormat('H:i', $restriction['time_end']);
                    }
                    else{
                        $hour_end = Carbon::createFromFormat('H:i:s', $restriction['time_end']);
                    }
                    // booking hour
                    if (substr_count($fillable['booking_time_start'], ':')==1) {
                        $booking_hour = Carbon::createFromFormat('H:i', $fillable['booking_time_start']);
                        $booking_day_time   = Carbon::createFromFormat('Y-m-d H:i', $fillable['date_of_booking'].' '.$fillable['booking_time_start']);
                    }
                    else{
                        $booking_hour = Carbon::createFromFormat('H:i:s', $fillable['booking_time_start']);
                        $booking_day_time   = Carbon::createFromFormat('Y-m-d H:i:s', $fillable['date_of_booking'].' '.$fillable['booking_time_start']);
                    }
                    // day of week for the booking
                    $booking_day        = Carbon::createFromFormat('Y-m-d', $fillable['date_of_booking'])->format('w');

                    // we check the day first
                    if (!in_array($booking_day, $selected_days)){
                        // not in selected day
                        $status = false;
                    }
                    // we check the hours interval second
                    elseif ($booking_hour->lte($hour_start) || $booking_hour->gte($hour_end)){
                        // not in selected time period
                        $status = false;
                    }

                    $special_restrictions = json_decode($restriction['special_permissions']);
                    if (sizeof($special_restrictions)>=1){
                        if (!isset($special_restrictions->special_days_ahead)){
                            $special_restrictions->special_days_ahead = 1;
                        }

                        $now_day = Carbon::now();
                        if ($special_restrictions->special_current_day=='1') {
                            // we calculate the time when this period can be booked - start interval
                            $start_interval = Carbon::instance($booking_day_time)->subDays($special_restrictions->special_days_ahead)->startOfDay();
                            // we calculate the end interval
                            $end_interval   = Carbon::instance($booking_day_time)->endOfDay();
                        }
                        else{
                            // we calculate the time when this period can be booked - start interval
                            $start_interval = Carbon::instance($booking_day_time)->subDays($special_restrictions->special_days_ahead)->startOfDay();
                            // we calculate the end interval
                            $end_interval   = Carbon::instance($booking_day_time)->subDays(1)->endOfDay();
                        }
                        //echo $now_day->toDateTimeString().' : '.$start_interval->toDateTimeString().' to '.$end_interval->toDateTimeString(); //exit;

                        if ($now_day->gte($start_interval) && $now_day->lt($end_interval)){
                            //$status = true;
                        }
                        else{
                            $status = false;
                        }
                    }

                    $time_of_day_result[] = $status;
                }
                break;
                case 'open_bookings' : {
                    $the_open_restrictions = (int)$restriction['value'][0];
                    if ( $nr_of_open_bookings >= $the_open_restrictions ){
                        $payment_type = 'cash';
                        return $payment_type;
                    }
                }
                break;
                case 'cancellation' : {
                    // do not need it here
                }
                break;
                case 'price' : {
                    // do not need it here
                }
                break;
                case 'included_activity' : {
                    $activities_in = json_decode($restriction['value']);
                    if (!in_array($resource_category->category_id, $activities_in)){
                        $payment_type = 'cash';
                        return $payment_type;
                    }
                }
                break;
                case 'booking_time_interval' : {
                    // booking must take place x hours from now
                    $hours_from_now      = $restriction['min_value'];
                    // bookings must be made until y hours from now
                    $hours_until_booking = $restriction['max_value'];

                    if (substr_count($fillable['booking_time_start'], ':')==1) {
                        $booking_date_time = Carbon::createFromFormat('Y-m-d H:i', $fillable['date_of_booking'] . ' ' . $fillable['booking_time_start']);
                    }
                    else{
                        $booking_date_time = Carbon::createFromFormat('Y-m-d H:i:s', $fillable['date_of_booking'] . ' ' . $fillable['booking_time_start']);
                    }
                    $closest_booking_date_time = Carbon::now()->addHours($hours_from_now);
                    $farthest_booking_date_time = Carbon::now()->addHours($hours_until_booking);

                    if (!$booking_date_time->gte($closest_booking_date_time) || !$booking_date_time->lt($farthest_booking_date_time)){
                    //if (!$booking_date_time->gte($closest_booking_date_time) || !$booking_date_time->lt($farthest_booking_date_time)){
                        $payment_type = 'cash';
                        return $payment_type;
                    }
                }
                break;
            }
        }

        foreach ($time_of_day_result as $a){
            if ($a == true){
                $payment_type = 'membership';
                return $payment_type;
            }
            else{
                $payment_type = 'cash';
            }
        }

        return $payment_type;
    }

    /**
     * Get user bookings with selected statuses
     * @param $userID
     * @param array $status
     * @return array
     */
    public static function get_user_bookings($userID, $status=['pending','active','paid','unpaid','old','canceled']){
        $bookings = [];

        $all_bookings = Booking::where('for_user_id','=',$userID)->whereIn('status', $status)->get();
        //xdebug_var_dump($all_bookings);
        if ($all_bookings){
            foreach($all_bookings as $booking){
                $bookings[] = ['id'=>$booking->id, 'status'=>$booking->status];
            }
        }
        return $bookings;
    }

    /**
     * Check for expired pending bookings
     * @param int $time
     * @param int $admin_time
     */
    public static function check_for_expired_pending_bookings($time = 60, $admin_time = 300){
        $now_time = Carbon::now();
        $open_bookings = Booking::where('status','=','pending')->get();
        if ($open_bookings){
            foreach ($open_bookings as $booking){
                $by_user = User::where('id','=',$booking->by_user_id)->first();

                $updatedAt = Carbon::createFromFormat('Y-m-d H:i:s', $booking->updated_at);
                $dif = $now_time->diffInSeconds($updatedAt);
                if ($by_user->hasRole('front-user') && $dif>$time){
                    $booking->status = 'expired';
                    $booking->save();
                }
                elseif ($dif>$admin_time){
                    $booking->status = 'expired';
                    $booking->save();
                }
            }
        }
    }

    /**
     * Checked for passed bookings that are still open/active
     * @return array
     */
    public static function check_for_passed_bookings(){
        /* Need to add resource and different time intervals or time period for each booking based on resource */

        // check for yesterday bookings
        $bookings = Booking::where('date_of_booking','<',Carbon::now()->toDateString())
                ->whereIn('status',['pending','active'])
                ->get();
        foreach($bookings as $booking){
            if ($booking->payment_type=='membership'){
                // membership plan, so we mark it as old since no other action is required
                $booking->status = 'old';
            }
            elseif ($booking->payment_type=='cash'){
                // get invoice for booking
                $invoice = BookingInvoice::find($booking->invoice_id);
                if ($invoice){
                    if ($invoice->status == 'completed') {
                        // invoice found and paid
                        $booking->status = 'paid';
                    }
                    else{
                        // invoice found and unpaid or waiting
                        $booking->status = 'unpaid';
                    }
                }
                else{
                    // no invoice created
                    $booking->status = 'unpaid';
                }
            }
            else{
                $booking->status = 'old';
            }

            $booking->save();
        }

        // check for this half hour bookings
        $bookings = Booking::where('date_of_booking','=',Carbon::now()->toDateString())
            ->where('booking_time_start','<=',Carbon::now()->toTimeString())
            ->where('booking_time_stop','>',Carbon::now()->toTimeString())
            ->where('status','=','pending')
            ->get();
        foreach($bookings as $booking){
            $booking->status = 'active';
            $booking->save();
        }

        // check for today bookings that passed, with active or pending status
        $bookings = Booking::where('date_of_booking','=',Carbon::now()->toDateString())
                ->where('booking_time_stop','<=',Carbon::now()->toTimeString())
                ->whereIn('status',['active', 'pending'])
                ->get();
        foreach($bookings as $booking){
            if ($booking->payment_type=='membership'){
                // membership plan, so we mark it as old since no other action is required
                $booking->status = 'old';
            }
            elseif ($booking->payment_type=='cash'){
                // get invoice for booking
                $invoice = BookingInvoice::find($booking->invoice_id);
                if ($invoice){
                    if ($invoice->status == 'completed') {
                        // invoice found and paid
                        $booking->status = 'paid';
                    }
                    else{
                        // invoice found and unpaid or waiting
                        $booking->status = 'unpaid';
                    }
                }
                else{
                    // no invoice created
                    $booking->status = 'unpaid';
                }
            }
            else{
                $booking->status = 'old';
            }

            $booking->save();
        }

        return ['success'=>true];
    }

    /**
     * Get booking summary for the selected search_key
     * @param Request $request
     * @return array
     */
    public function bookings_summary(Request $request){
        if (!Auth::check()) {
            //return redirect()->intended(route('admin/login'));
            return ['error' => 'Authentication Error'];
        }
        else{
            $user = Auth::user();
        }

        $is_staff = false;
        if (!$user->hasRole(['front-member','front-user'])){
            $is_staff = true;
        }

        $this->check_for_expired_pending_bookings();

        $vars = $request->only('all_bookings');
        $keys = explode(',',$vars['all_bookings']);
        $membership_nr = 0;
        $cash_nr = 0;
        $cash_amount = 0;
        $recurring_nr = 0;
        $recurring_cash = 0;

        if (sizeof($keys)>0){
            foreach($keys as $key){
                if ($key==''){ continue; }

                if ($is_staff){
                    $booking = Booking::where('search_key', '=', $key)->get()->first();
                }
                else {
                    $booking = Booking::where('by_user_id', '=', $user->id)
                        ->where('search_key', '=', $key)
                        ->get()->first();
                }

                if ($booking){
                    // get resource VAT
                    $resourceDetails = ShopResource::with('vatRate')->where('id','=',$booking->resource_id)->get()->first();

                    //xdebug_var_dump($booking);
                    if ($booking['payment_type']=='cash'){
                        $cash_nr+=1;
                        $cash_amount+= ($booking['payment_amount']*(100+$resourceDetails->vatRate->value))/100;
                    }
                    elseif ($booking['payment_type']=='recurring'){
                        $recurring_nr++;
                        $recurring_cash+=($booking['payment_amount']*(100+$resourceDetails->vatRate->value))/100;
                    }
                    else{
                        $membership_nr+=1;
                    }
                }
            }

            return ['success' => 'true',
                    'membership_nr'     => $membership_nr,
                    'recurring_nr'      => $recurring_nr,
                    'recurring_cash'    => $recurring_cash,
                    'cash_nr'       => $cash_nr,
                    'cash_amount'   => $cash_amount.' NOK' ];
        }
        else{
            return ['error' => 'No bookings found to confirm. Please make the booking process again. Remember you have 60 seconds to complete the booking before it expires.'];
        }
    }

    /**
     * Confirm the bookings sent using selected_bookings variable
     * @param Request $request
     * @return array
     */
    public function confirm_bookings(Request $request){
        if (!Auth::check()) {
            //return redirect()->intended(route('admin/login'));
            return ['error' => 'Authentication Error'];
        }
        else{
            $user = Auth::user();
        }

        $is_staff = false;
        if (!$user->hasRole(['front-member','front-user'])){
            $is_staff = true;
        }

        $this->check_for_expired_pending_bookings();

        $vars = $request->only('selected_bookings');
        $keys = explode(',',$vars['selected_bookings']);
        $return_key = [];

        if (sizeof($keys)>0){
            $email_confirm = [];
            $booking_invoices = [];
            foreach($keys as $key){
                if ( strlen($key)<5 ){ continue; }
                $return_key[] = $key;
                if ($is_staff) {
                    $booking = Booking::where('status', '=', 'pending')->where('search_key', '=', $key)->get()->first();
                    if (!$booking){
                        continue;
                    }

                    Activity::log([
                        'contentId'     => $user->id,
                        'contentType'   => 'bookings',
                        'action'        => 'Confirm Booking - by staff',
                        'description'   => 'Booking confirmed for booking key : '.$key,
                        'details'       => 'User Email : '.$user->email,
                        'updated'       => true,
                    ]);
                }
                else{
                    $booking = Booking::where('status', '=', 'pending')->where('by_user_id', '=', $user->id)->where('search_key', '=', $key)->get()->first();
                    if (!$booking){
                        continue;
                    }

                    Activity::log([
                        'contentId'     => $user->id,
                        'contentType'   => 'bookings',
                        'action'        => 'Confirm Booking - by member',
                        'description'   => 'Booking confirmed for booking key : '.$key,
                        'details'       => 'User Email : '.$user->email,
                        'updated'       => true,
                    ]);
                }

                if ($booking->payment_type=="membership" && $booking->membership_id==-1){
                    $userMembership = UserMembership::where('user_id','=',$booking->for_user_id)->where('status','=','active')->get()->first();
                    if ($userMembership){
                        $booking->membership_id = $userMembership->id;
                    }
                }
                elseif ($booking->payment_type=="cash" || $booking->payment_type=="recurring"){
                    // check if the membership assigned to the user is the same as the one in the booking

                    if (!isset($booking_invoices[$booking->by_user_id])){
                        // echo $booking->invoice_id;
                        if ($booking->invoice_id == -1) {
                            // add invoice
                            $book_invoice = $booking->add_invoice();
                        }
                        else {
                            // get invoice
                            $book_invoice = BookingInvoice::where('id', '=', $booking->invoice_id)->get()->first();
                        }
                        $booking_invoices[$booking->by_user_id] = $book_invoice;
                        //xdebug_var_dump($book_invoice);
                    }
                    else {
                        $book_invoice = $booking_invoices[$booking->by_user_id];
                    }

                    $book_invoice->add_invoice_item($booking->id);
                    $booking->invoice_id = $book_invoice->id;

                    $bookingItem        = BookingInvoiceItem::where('booking_id', '=', $booking->id)->get()->first();
                    $general_invoice    = Invoice::where('invoice_type', '=','booking_invoice')->where('invoice_reference_id','=',$book_invoice->id)->get()->first();
                    $is_item_added      = InvoiceItem::where('item_type','=','booking_invoice_item')->where('item_reference_id','=',$bookingItem->id)->get()->first();
                    if (!$is_item_added) {
                        $generalFillable = [
                            'item_name'         => $bookingItem->location_name . ', room ' . $bookingItem->resource_name . '
                                                    - ' . $bookingItem->booking_time_interval . ' on ' . $bookingItem->booking_date . ' ',
                            'item_type'         => 'booking_invoice_item',  // type of the item : membership payment, coupon code, discounts, bookings, products
                            'item_reference_id' => $bookingItem->id,        // the ID from the table
                            'quantity'          => $bookingItem->quantity,  // number of items
                            'price'             => $bookingItem->price,     // base price
                            'vat'               => $bookingItem->vat,       // VAT for the product group
                            'discount'          => $bookingItem->discount,  // applied discount
                        ];
                        $abcd = $general_invoice->add_invoice_item($generalFillable);
                    }
                }

                $booking->status = 'active';
                $booking->save();

                $booking_details = $booking->get_summary_details(false);
                $email_confirm[$booking->for_user_id][] = $booking_details;
            }

            foreach($email_confirm as $player_id=>$booking_details) {
                // send_email_to_user
                $player = User::where('id', '=', $player_id)->get()->first();
                if (sizeof($booking_details)>1){
                    $email_title = 'Booking System - Several bookings were created';
                }
                else{
                    $booking_details = $booking_details[0];
                    $email_title = 'Booking System - Your booking for ' . $booking_details["bookingDate"] . ' was created';
                }

                $beautymail = app()->make(Beautymail::class);
                $beautymail->send('emails.booking.new_booking', ['player' => $player, 'booking' => $booking_details, 'logo' => ['path' => 'http://sqf.se/wp-content/uploads/2012/12/sqf-logo.png']], function ($message) use ($player, $email_title) {
                    $message
                        ->from('bogdan@bestintest.eu')
                        ->to($player->email, $player->first_name.' '.$player->middle_name.' '.$player->last_name)
                        //->to('stefan.bogdan@ymail.com', $player->first_name . ' ' . $player->middle_name . ' ' . $player->last_name)
                        ->subject($email_title);
                });
            }
        }
        else{
            return ['error' => 'No bookings found to confirm. Please make the booking process again. Remember you have 60 seconds to complete the booking before it expires.'];
        }

        return $return_key;
    }

    /**
     * Cancel pending bookings, using selected_bookings variable sent via POST
     * @param Request $request
     * @return array
     */
    public function cancel_pending_bookings(Request $request){
        if (!Auth::check()) {
            //return redirect()->intended(route('admin/login'));
            return ['success'=>false, 'title'=>'Credentials error', 'errors' => 'Please refresh the page and try again'];
        }
        else{
            $user = Auth::user();
        }

        $is_staff = false;
        if (!$user->hasRole(['front-member','front-user'])){
            $is_staff = true;
        }
        $this->check_for_expired_pending_bookings();

        $vars = $request->only('selected_bookings');
        $keys = explode(',',$vars['selected_bookings']);
        if (sizeof($keys)>0){
            foreach($keys as $key){
                if ($key==''){ continue; }
                if ($is_staff){
                    Booking::where('status','=','pending')->where('search_key','=',$key)->update(['status'=>'canceled']);

                    Activity::log([
                        'contentId'     => $user->id,
                        'contentType'   => 'bookings',
                        'action'        => 'Confirm Booking - by staff',
                        'description'   => 'Booking confirmed for booking key : '.$key,
                        'details'       => 'User Email : '.$user->email,
                        'updated'       => true,
                    ]);
                }
                else{
                    Booking::where('status','=','pending')->where('by_user_id','=',$user->id)->where('search_key','=',$key)->update(['status'=>'canceled']);

                    Activity::log([
                        'contentId'     => $user->id,
                        'contentType'   => 'bookings',
                        'action'        => 'Confirm Booking - by member',
                        'description'   => 'Booking confirmed for booking key : '.$key,
                        'details'       => 'User Email : '.$user->email,
                        'updated'       => true,
                    ]);
                }
            }

            return ['success' => 'true', 'message' => 'All is good.'];
        }
        else{
            return ['error' => 'No bookings found to confirm. Please make the booking process again. Remember you have 60 seconds to complete the booking before it expires.'];
        }
    }

    /**
     * Get all bookings for specific user with status different than expired
     * @param int $userID
     * @return array
     */
    public function get_user_booking_archive($userID = -1){
        if (Auth::check()) {
            $user = Auth::user();
        }
        else{
            return [];
        }

        if ($userID == -1){
            $userID = $user->id;
        }

        $data = [];

        $allBookings = Booking::with('notes')->where('by_user_id','=',$userID)->orWhere('for_user_id','=',$userID)->get();
        if ($allBookings){
            foreach($allBookings as $booking){
                if ($booking->status == 'expired'){
                    continue;
                }

                $format_date = Carbon::createFromFormat('Y-m-d H:i:s', $booking->date_of_booking.' '.$booking->booking_time_start);
                $bookingFor = User::find($booking->for_user_id);
                $location = ShopLocations::find($booking->location_id);
                $resource = ShopResource::find($booking->resource_id);
                $activity = ShopResourceCategory::find($resource->category_id);
                $status = '';

                if ($booking->status != 'active'){
                    if ($booking->status=="unpaid"){
                        $status = '<a href="#'.$booking->search_key.'" class="btn yellow-gold">Pay Invoice</a>';
                    }
                    elseif ($booking->status=="paid"){
                        $status = '<a href="#'.$booking->search_key.'" class="btn green-turquoise">Invoice</a>';
                    }

                    if (sizeof($booking->notes)>0){
                        $status.= '<span data-id="' . $booking->search_key . '" class="details_booking btn blue-sharp">Details</span> ';;
                    }
                }
                else{
                    if ($this->can_cancel_booking($booking->id)) {
                        $status = '<span data-id="' . $booking->search_key . '" class="cancel_booking btn grey-silver">Cancel</span> ';
                    }

                    if ($booking->by_user_id==$userID && Carbon::now()->lt($format_date)) {
                        $status.= ' <span data-id="' . $booking->search_key . '" class="modify_booking btn blue-steel">Modify</span>';
                    }
                }

                $data[] = [
                    $format_date->format('d-M-Y, H:i'),
                    $bookingFor->first_name.' '.$bookingFor->middle_name.' '.$bookingFor->last_name,
                    $location->name.' - '.$resource->name,
                    $activity->name,
                    $booking->status,
                    $status
                ];
            }
        }

        $bookings = [
            "data" => $data
        ];

        return $bookings;
    }

    /**
     * Check if an active booking can be canceled
     * @param $id
     * @param int $hours
     * @return bool
     */
    public function can_cancel_booking($id, $hours = 6){
        $can_cancel = true;

        $booking = Booking::find($id);
        if ($booking){
            $bookingStartDateTime = Carbon::createFromFormat('Y-m-d H:i:s', $booking->date_of_booking.' '.$booking->booking_time_start);
            $can_cancel_hours = $hours;

            $user = User::where('id','=',$booking->for_user_id)->get()->first();
            if (!$user){
                $can_cancel = false;
            }
            else{
                $restrictions = $user->get_membership_restrictions();
                foreach ($restrictions as $restriction){
                    if ($restriction['name'] == 'cancellation' && $restriction['value']>$can_cancel_hours) {
                        $can_cancel_hours = $restriction['value'];
                    }
                }

                $actualTime = Carbon::now()->addHours($can_cancel_hours);
                if ($actualTime->lt($bookingStartDateTime)){
                    $can_cancel = true;
                }
                else{
                    $can_cancel = false;
                }
            }
        }
        else{
            $can_cancel = false;
        }

        return $can_cancel;
    }

    /**
     * Change status of booking to no_show and send emails if requested
     * @param Request $request
     * @return array
     */
    public function not_show_status_change(Request $request){
        if (!Auth::check()) {
            //return redirect()->intended(route('admin/login'));
            return ['error' => 'Authentication Error'];
        }
        else{
            $user = Auth::user();
        }

        $vars = $request->only('search_key', 'add_invoice', 'custom_message', 'default_message', 'private_message');
        $booking = Booking::where('search_key','=',$vars['search_key'])->get()->first();
        if ($booking){
            $fillable_visible = [
                'by_user_id' => $user->id,
                'note_title' => '',
                'note_body'  => '',
                'note_type'  => 'booking_status_changed_to_noshow',
                'privacy'    => 'everyone',
                'status'     => 'unread'
            ];

            if ($vars['default_message']==-1){
                // we have custom message
                $fillable_visible['note_title'] = 'Booking status changed to NoShow';
                $fillable_visible['note_body'] = $vars['default_message'];
            }
            else{
                // we have default message
                // $message = get_default_message();
                $message = ['body'=>'Default message body', 'title'=>'Default message title'];
                $fillable_visible['note_body'] = $message['body'];
                $fillable_visible['note_title'] = $message['title'];
            }

            $a = $booking->add_note($fillable_visible);
            if (strlen($vars['private_message'])>5) {
                $fillable_private = [
                    'by_user_id' => $user->id,
                    'note_title' => 'Booking status - not shown',
                    'note_body' => $vars['private_message'],
                    'note_type' => 'booking_status_changed_to_noshow',
                    'privacy' => 'employees',
                    'status' => 'unread'
                ];
                $booking->add_note($fillable_private);

                Activity::log([
                    'contentId'     => $user->id,
                    'contentType'   => 'booking_notes',
                    'action'        => 'Add note to Booking',
                    'description'   => 'New booking note : '.$vars['private_message'],
                    'details'       => 'User Email : '.$user->email,
                    'updated'       => false,
                ]);
            }

            if ($vars['add_invoice']==1){
                $booking_invoice = $booking->add_invoice();
                if ($booking_invoice){
                    $booking->invoice_id = $booking_invoice->id;

                    Activity::log([
                        'contentId'     => $user->id,
                        'contentType'   => 'booking_invoices',
                        'action'        => 'Add invoice to booking',
                        'description'   => 'New booking invoice created : booking ID '.$booking_invoice->id,
                        'details'       => 'User Email : '.$user->email,
                        'updated'       => false,
                    ]);
                }
            }
            $booking->status = 'noshow';
            $booking->save();

            Activity::log([
                'contentId'     => $user->id,
                'contentType'   => 'bookings',
                'action'        => 'Update Booking',
                'description'   => 'No show status change for booking ID : '.$booking->id,
                'details'       => 'User Email : '.$user->email,
                'updated'       => false,
            ]);

            return ['success' => 'true', 'message' => 'All is good.'];
        }
        else{
            return ['error' => 'No bookings found to confirm. Please make the booking process again. Remember you have 60 seconds to complete the booking before it expires.'];
        }
    }

    /**
     * Bookings Calendar View - Admin part
     * @param int $date_selected
     * @return - not logged in : \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     *         - logged in : array
     */
    public function location_calendar_day_view($date_selected = 0, $selected_location = 0, $selected_activity = 0){
        $user = Auth::user();
        if (!$user || !$user->is_back_user()) {
            return redirect()->intended(route('admin/login'));
        }

        $settings = UserSettings::get_general_settings($user->id, ['settings_preferred_location','settings_preferred_activity']);

        // Date validation and variables assignation
        if ($date_selected==0){
            $date_selected = Carbon::now()->format('Y-m-d');
        }
        else{
            $date_selected = Carbon::createFromFormat('d-m-Y',$date_selected)->format('Y-m-d');
        }
        $header_vals['date_selected'] = Carbon::createFromFormat('Y-m-d',$date_selected)->format('d-m-Y');
        $header_vals['next_date'] = Carbon::createFromFormat('Y-m-d',$date_selected)->addDay(1)->format('d-m-Y');
        $header_vals['prev_date'] = Carbon::createFromFormat('Y-m-d',$date_selected)->addDay(-1)->format('d-m-Y');

        // location validation and variables assignation
        if ($selected_location==0){
            $default_location = isset($settings['settings_preferred_location'])?$settings['settings_preferred_location']:7;
        }
        else{
            $default_location = $selected_location;
        }
        $location_found = false;
        $all_locations = ShopLocations::select('id','name')->where('visibility','=','public')->orderBy('name','ASC')->get();
        foreach ($all_locations as $location){
            if ($location->id==$default_location){
                $location_found=true;
            }
        }
        if ($location_found==false){
            // redirect not found
            $default_location = 7;
        }
        unset($location);
        $header_vals['selected_location'] = $default_location;

        $location = ShopLocations::select('id','name')->where('id','=',$default_location)->get()->first();

        // activity/category validation and variables assignation
        $locationActivities = [];
        $checkLocations = [];
        $first_line_activity = -1;
        $availableActivities = ShopResource::with('category')->where('location_id','=',$location->id)->groupBy('category_id')->get();
        if ($availableActivities){
            foreach($availableActivities as $oneActivity){
                if ($first_line_activity == -1){
                    $first_line_activity = $oneActivity->category_id;
                }
                $locationActivities[] = ['id' => $oneActivity->category_id, 'name' => $oneActivity->category->name];
                $checkLocations[] = $oneActivity->category_id;
            }
        }

        if ($selected_activity==0){
            $default_activity = isset($settings['settings_preferred_activity'])?$settings['settings_preferred_activity']:$first_line_activity;
        }
        else{
            $default_activity = $selected_activity;
        }

        if (!in_array($default_activity, $checkLocations)){
            // redirect to default first activity in the location
            return redirect()->intended(route('bookings/location_calendar_day_view_all',['day'=>$header_vals['date_selected'], 'location'=>$location->id, 'activity'=>$first_line_activity]));
        }

        $header_vals['selected_activity'] = $default_activity;

        $location = ShopLocations::select('id','name')->where('id','=',$default_location)->get()->first();
        $activity = ShopResourceCategory::where('id','=',$default_activity)->get()->first();

        $resources_ids = [];
        $resources = ShopResource::where('location_id','=',$location->id)->where('category_id','=',$activity->id)->get();
        if ($resources){
            foreach($resources as $resource){
                $resources_ids[] = $resource->id;
            }
        }

        $locationOpeningHours = $location->get_opening_hours($date_selected);
        //xdebug_var_dump($locationOpeningHours); exit;
        if (isset($locationOpeningHours['open_at']) && isset($locationOpeningHours['close_at'])){
            $open_at  = $locationOpeningHours['open_at'];
            $close_at = $locationOpeningHours['close_at'];
        }
        else{
            $open_at  = '07:00';
            $close_at = '23:00';
        }
        $hours_interval     = $this->make_hours_interval($date_selected, $open_at, $close_at, 30, true, false);
        $location_bookings  = $this->get_location_bookings($date_selected, $location->id, $resources_ids, $hours_interval);

    //$this->make_hours_interval1($date_selected, $open_at, $close_at, 30, false, false);

        $resources_ids = [];
        foreach($resources as $resource){
            $resources_ids[] = ['name'=>$resource->name, 'id'=>$resource->id];
        }

        $membership_plans = MembershipPlan::where('id','!=','1')->where('status','=','active')->get()->sortBy('name');

        $memberships = MembershipPlan::where('id','!=',-1)->get();
        $membership_legend = [];
        if ($memberships){
            foreach($memberships as $membership){
                $membership_legend[] = [
                    'name' => $membership->name,
                    'status'=> $membership->status,
                    'color' => $membership->plan_calendar_color
                ];
            }
        }

        $buttons_color = [
            'is_show'           => 'bg-green-jungle bg-font-green-jungle',
            'is_no_show'        => 'bg-red-thunderbird bg-font-red-thunderbird',
            'show_btn_active'   => 'btn-default',

            'is_paid_cash'      => 'bg-blue bg-font-blue',
            'is_paid_card'      => 'bg-purple bg-font-purple',
            'is_paid_online'    => 'bg-yellow-haze bg-font-yellow-haze',
            'payment_issues'    => 'bg-red-thunderbird bg-font-red-thunderbird',
            'payment_btn_active'=> 'btn-default',

            'more_btn_active'   => 'btn-default',

            'is_disabled'       => 'bg-default bg-font-default',
        ];

        $breadcrumbs = [
            'Home'          => route('admin'),
            'Bookings'      => route('admin'),
            'Calendar view' => route('bookings/location_calendar_day_view', ['day'=>\Carbon\Carbon::now()->format('d-m-Y')]),
        ];
        $sidebar_link= 'admin-bookings-calendar_view';
        return view('admin/bookings/calendar_location_per_day', [
            'breadcrumbs'   => $breadcrumbs,
            'in_sidebar'    => $sidebar_link,
            'time_intervals'    => $hours_interval,
            'location_bookings' => $location_bookings['hours'],
            'resources'     => $resources_ids,
            'button_color'  => $buttons_color,
            'header_vals'   => $header_vals,
            'all_locations' => $all_locations,
            'all_activities'=> $locationActivities,
            'is_close_menu' => true,
            'memberships'   => $membership_plans,
            'membership_legend'=> $membership_legend
        ]);
    }

    /**
     * Returns time interval for days based on the requirements of each specific resource/category of bookings
     * @param $date_selected
     * @param string $start_time
     * @param string $end_time
     * @param int $time_period
     * @param bool $show_all
     * @param bool $show_last
     * @return array
     */
    public function make_hours_interval_old($date_selected, $start_time='07:00', $end_time='23:00', $time_period=30, $show_all = false, $show_last = false){
        $dateSelected = Carbon::createFromFormat("Y-m-d", $date_selected);
        if (!$dateSelected){
            return [];
        }

        $hours = [];
        // if selected day = today
        if ( $show_all == false ) {
            $currentTimeHour    = Carbon::now()->format('H');
            $currentTimeMinutes = Carbon::now()->format('i');
        }
        else {
            $currentTimeHour    = Carbon::createFromFormat('H:i',$start_time)->format('H');
            $currentTimeMinutes = Carbon::createFromFormat('H:i',$start_time)->format('i');
        }

        if ($currentTimeMinutes>=0 && $currentTimeMinutes<30){
            $currentTimeMinutes = 30;
        }
        else{
            $currentTimeMinutes = 0;
            $currentTimeHour = (int)$currentTimeHour+1;
        }

        $begin  = Carbon::today();
        $v1 = $dateSelected->format("Y-m-d");
        $v2 = Carbon::now()->format("Y-m-d");
        if ( $v1==$v2) {
            $begin->addHour($currentTimeHour);
            $begin->addMinutes($currentTimeMinutes);
            $begin->addMinutes(-$time_period);
        }
        else{
            $begin->addHour($currentTimeHour);
        }
        $end    = Carbon::tomorrow();
        if ($show_last==false){
            $end->addMinutes(-60);
        }
        else{
            $end->addMinutes(-30);
        }

        $interval   = DateInterval::createFromDateString($time_period.' minutes');
        $period     = new DatePeriod($begin, $interval, $end);

        $current_time = Carbon::now()->format( "H:i" );
        foreach ( $period as $dt ) {
            $key = $dt->format( "H:i" );
            if ( ($key<$current_time && $v1==$v2) || ($v1<$v2) ) {
                $hours[$key] = ['color_stripe' => "bg-grey-salt bg-font-grey-salt"];
            }
            else{
                $hours[$key] = ['color_stripe' => ""];
            }
        }
//xdebug_var_dump($hours);
        return $hours;
    }

//  -----------------
    /**
     * Returns time interval for days based on the requirements of each specific resource/category of bookings
     * @param $date_selected
     * @param string $start_time
     * @param string $end_time
     * @param int $time_period
     * @param bool $show_all
     * @param bool $show_last
     * @return array
     */
    public static function make_hours_interval($date_selected, $start_time='07:00', $end_time='23:00', $time_period=30, $show_all = false, $show_last = false){
//echo 'Date : '.$date_selected.' ; time_start : '.$start_time.' ; time_end : '.$end_time;
        $dateSelected = Carbon::createFromFormat("Y-m-d H:i", $date_selected.' 00:00');
        if (!$dateSelected){
            return [];
        }

        $startTime  = Carbon::createFromFormat('H:i',$start_time);
        $endTime    = Carbon::createFromFormat('H:i',$end_time);

        $begin = Carbon::instance($dateSelected);
        if ($dateSelected->format('Y-m-d') == Carbon::today()->format('Y-m-d')){
            // it's today
            $begin->addHours($startTime->format('H'));
            $begin->addMinutes($startTime->format('i'));

            if ($show_all == false){
                while ($begin->lte(Carbon::now())){
                    $begin->addMinutes($time_period);
                }
            }
        }
        else{
            // it's another day
            $begin->addHours($startTime->format('H'));
            $begin->addMinutes($startTime->format('i'));
        }
//xdebug_var_dump($begin);
        $end = Carbon::instance($dateSelected);
        $end->addHours($endTime->format('H'));
        $end->addMinutes($endTime->format('i'));
        if ($show_last == true){
            $end->addMinute();
        }
        else{
            // we leave it like this
        }
//xdebug_var_dump($end);
        $interval   = DateInterval::createFromDateString($time_period.' minutes');
        $period     = new DatePeriod($begin, $interval, $end);
        $hours      = [];

        $current_time   = Carbon::now()->format( "H:i" );
        $yesterday      = $dateSelected->lt(Carbon::today()) ? true : false;
        $today          = $dateSelected->eq(Carbon::today()) ? true : false;
        foreach ( $period as $dt ) {
            $key = $dt->format( "H:i" );
            if ( ($key<$current_time && $today) || $yesterday ) {
                $hours[$key] = ['color_stripe' => "bg-grey-salt bg-font-grey-salt"];
            }
            else{
                $hours[$key] = ['color_stripe' => ""];
            }
        }
//xdebug_var_dump($hours); exit;
        return $hours;
    }
//  -----------------

    /**
     * Get location bookings based on location, resources, time intervals
     * @param $date_selected - date variable in dd-mm-yyyy format
     * @param $location - array of IDs or single id for ShopLocations
     * @param $resources - array of IDs or id for shopResources
     * @param $hours - array of hours interval (start of booking times)
     * @return array - returns 'hours' array and 'bookings' array
     *               - hours - intervals with colors for booked or free colors
     *               - bookings - individual bookings for time interval keys
     */
    public function get_location_bookings($date_selected, $location, $resources, $hours){
        if (Auth::check()) {
            $user = Auth::user();
            BookingController::check_for_expired_pending_bookings();
        }

        $bookings = [];
        // check if we get today or 10 days from today
        $dateSelected = Carbon::createFromFormat("Y-m-d", $date_selected);
        if (!$dateSelected){
            return [];
        }

        foreach($hours as $key=>$hour){
            $hours[$key] = $this->check_date_time_bookings($date_selected, $key, $location, $resources, true);
        }

        $returnArray = ["hours"=>$hours, "bookings"=>$bookings];

        return $returnArray;
    }

    /**
     * Returns bookings for specified data and time for a location's/resource's
     * @param $date = date in dd-mm-yyyy format
     * @param $time = start time of booking in hh:mm format
     * @param $location = array of ShopLocation IDs or single id
     * @param $resource = array of ShopResource IDs or single id
     * @param bool $show_more = shows more information on the admin calendar view
     * @return array = array of bookings for data/time/resource specified at the beginning
     */
    public function check_date_time_bookings($date, $time, $location, $resource, $show_more=false){
        $booking_details = [];
        $buttons_color = [
            'is_show'           => '',
            'is_no_show'        => '',
            'show_btn_active'   => '',
            'is_paid_cash'      => '',
            'is_paid_card'      => '',
            'is_paid_online'    => '',
            'payment_issues'    => '',
            'payment_btn_active'=> '',
            'more_btn_active'   => '',
            'is_disabled'       => '',
        ];

        // get custom membership colors
        $allPlans = MembershipPlan::select('id', 'plan_calendar_color')->get();
        $plansCalendarColor = [];
        if ($allPlans){
            foreach($allPlans as $thePlan){
                $plansCalendarColor[$thePlan->id] = $thePlan->plan_calendar_color;
            }
        }

        $q = DB::table('bookings');
        if (is_array($location)){
            $q->whereIn('location_id', $location);
        }
        else{
            $q->where('location_id','=',$location);
        }

        if (is_array($resource)){
            $q->whereIn('resource_id', $resource);
        }
        else{
            $q->where('resource_id','=',$resource);
        }
        $q->where('date_of_booking','=',$date)
          ->whereIn('status',['pending','active','paid','unpaid','old','noshow'])
          ->where('booking_time_start','=',$time);
        $bookings = $q->get();

        if ($bookings){
            /*$value = Cache::remember('users', 60, function() {
                return DB::table('users')->get();
            });

            foreach($value as $k=>$a){
                Cache::put('user-'.$a->id,$a,60);
            }*/

            foreach ($bookings as $booking){
                $formatted_booking = [
                    'search_key' => $booking->search_key,
                    'location' => $booking->location_id,
                    'resource' => $booking->resource_id,
                    'status'   => $booking->status,
                    'color_stripe' => 'bg-red-flamingo bg-font-red-flamingo'];

                if ($show_more){
                    $player = User::where('id','=',$booking->for_user_id)->get()->first();
                    //$player = Cache::get('user-'.$booking->for_user_id);
                    if ($player->hasRole(['front-user','front-member'])){
                        $player_link = route('admin/front_users/view_user',['id'=>$player->id]);
                    }
                    else{
                        $player_link = route('admin/back_users/view_user/',['id'=>$player->id]);
                    }

                    $formatted_booking['payment_type'] = $booking->payment_type;
                    $formatted_booking['payment_amount'] = $booking->payment_amount;
                    $formatted_booking['invoice'] = $booking->invoice_id;
                    $formatted_booking['player_name'] = $player->first_name.' '.$player->middle_name.' '.$player->last_name;
                    $formatted_booking['player_link'] = $player_link;
                    $formatted_booking['id'] = $booking->id;

                    if ($formatted_booking['payment_type'] == 'membership' && $booking->status!='pending'){
                        $formatted_booking['color_stripe'] = 'bg-green-haze bg-font-green-haze';

                        $userMembership = UserMembership::where('user_id','=',$booking->for_user_id)->where('id','=',$booking->membership_id)->get()->first();
                        if ($userMembership && isset($plansCalendarColor[$userMembership->membership_id])){
                            $formatted_booking['custom_color'] = $plansCalendarColor[$userMembership->membership_id].' !important';
                        }
                    }
                    elseif ($formatted_booking['payment_type'] == 'cash' && $booking->status!='pending'){
                        $formatted_booking['color_stripe'] = 'bg-yellow-gold bg-font-yellow-gold';
                        $formatted_booking['custom_color'] = $plansCalendarColor[1].' !important';
                    }
                    elseif ($formatted_booking['payment_type'] == 'recurring' && $booking->status!='pending'){
                        $formatted_booking['color_stripe'] = 'bg-purple-wisteria bg-font-purple-wisteria';
                    }
                    elseif($booking->status=='pending'){
                        $formatted_booking['color_stripe'] = 'bg-yellow-soft bg-font-yellow-soft';
                    }

                    $formatted_booking['button_show'] = 'is_disabled';
                    $formatted_booking['button_finance'] = 'is_disabled';
                    $formatted_booking['button_more'] = 'more_btn_active';
                    switch ($booking->status) {
                        case 'pending' :
                            // all disabled
                            $formatted_booking['button_more'] = 'is_disabled';
                            break;
                        case 'active' :
                            $formatted_booking['button_show'] = 'show_btn_active';
                            if ($booking->payment_type=="cash"){
                                $invoice = BookingInvoice::with('invoice_items')->with('financial_transaction')->find($booking->invoice_id);
                                if ($invoice){
                                    $invItemID = 0;
                                    foreach($invoice->invoice_items as $invItem){
                                        if ($invItem->booking_id==$booking->id){
                                            $invItemID = $invItem->id;
                                            break;
                                        }
                                    }

                                    foreach ($invoice->financial_transaction as $ab) {
                                        if ($ab->booking_invoice_item_id==$invItemID){
                                            $transaction = $ab;
                                            break;
                                        }
                                    }

                                    //xdebug_var_dump($transaction);
                                    //xdebug_var_dump($invoice->status);

                                    $formatted_booking['invoice_status'] = $invoice->status;
                                    switch ($invoice->status){
                                        case 'pending' :
                                            // the invoice is new and there was no payment tried for the amount
                                            $formatted_booking['button_finance'] = 'payment_btn_active';
                                            break;
                                        case 'ordered' :
                                            // we'll not use it
                                            break;
                                        case 'processing' :
                                            // a payment process started and the result is waiting
                                            if (isset($transaction)) {
                                                switch ($transaction->transaction_type) {
                                                    case 'cash' :
                                                        $formatted_booking['button_finance'] = 'is_paid_cash';
                                                        break;
                                                    case 'card' :
                                                        $formatted_booking['button_finance'] = 'is_paid_card';
                                                        break;
                                                    default :
                                                        $formatted_booking['button_finance'] = 'is_paid_online';
                                                        break;
                                                }
                                            }
                                            else{
                                                $formatted_booking['button_finance'] = 'payment_btn_active';
                                            }
                                            break;
                                        case 'completed' :
                                            // payment was done successfully
                                            switch($transaction->transaction_type){
                                                case 'cash' :
                                                    $formatted_booking['button_finance'] = 'is_paid_cash';
                                                    break;
                                                case 'card' :
                                                    $formatted_booking['button_finance'] = 'is_paid_card';
                                                    break;
                                                default :
                                                    $formatted_booking['button_finance'] = 'is_paid_online';
                                                    break;
                                            }
                                            break;
                                        case 'cancelled' :
                                            // invoice was canceled
                                            break;
                                        case 'declined' :
                                            // payment was declined
                                            $formatted_booking['button_finance'] = 'payment_btn_active';
                                            break;
                                        case 'incomplete' :
                                            // payment was incomplete
                                            $formatted_booking['button_finance'] = 'payment_issues';
                                            break;
                                        case 'preordered' :
                                            // we'll not use it
                                            break;
                                    }
                                }
                                else{
                                    // no invoice so the button is active and can be paid
                                    $formatted_booking['button_finance'] = 'payment_btn_active';
                                }
                            }
                            break;
                        case 'paid'   :
                        case 'unpaid' :
                            // show button was clicked
                            $formatted_booking['button_show'] = 'is_show';
                            $invoice = BookingInvoice::with('financial_transaction')->find($booking->invoice_id);
                            if ($invoice){
                                foreach ($invoice->financial_transaction as $ab) {
                                    $transaction = $ab;
                                    break;
                                }

                                $formatted_booking['invoice_status'] = $invoice->status;
                                switch ($invoice->status){
                                    case 'pending' :
                                        // the invoice is new and there was no payment tried for the amount
                                        $formatted_booking['button_finance'] = 'payment_btn_active';
                                        break;
                                    case 'ordered' :
                                        // we'll not use it
                                        break;
                                    case 'processing' :
                                        // payment was done successfully
                                    case 'completed' :
                                        // payment was done successfully
                                        switch($transaction->transaction_type){
                                            case 'cash' :
                                                $formatted_booking['button_finance'] = 'is_paid_cash';
                                                break;
                                            case 'card' :
                                                $formatted_booking['button_finance'] = 'is_paid_card';
                                                break;
                                            default :
                                                $formatted_booking['button_finance'] = 'is_paid_online';
                                                break;
                                        }
                                        break;
                                    case 'cancelled' :
                                        // invoice was canceled
                                        break;
                                    case 'declined' :
                                        // payment was declined
                                        $formatted_booking['button_finance'] = 'payment_btn_active';
                                        break;
                                    case 'incomplete' :
                                        // payment was incomplete
                                        $formatted_booking['button_finance'] = 'payment_issues';
                                        break;
                                    case 'preordered' :
                                        // we'll not use it
                                        break;
                                }
                            }
                            $formatted_booking['invoice_status'] = 'completed';
                            break;
                        case 'unpaid2' :
                            // show button was clicked
                            $formatted_booking['button_show'] = 'is_show';
                            $formatted_booking['invoice_status'] = 'pending';
                            break;
                        case 'old' :
                            // show button was clicked, player is show
                            $formatted_booking['button_show'] = 'is_show';
                            break;
                        case 'noshow' :
                            // the player is no show
                            $formatted_booking['button_show'] = 'is_no_show';
                            if ($booking->payment_type=="cash"){
                                $invoice = BookingInvoice::find($booking->invoice_id);
                                if ($invoice){
                                    foreach ($invoice->financial_transaction as $ab) {
                                        $transaction = $ab;
                                        break;
                                    }

                                    $formatted_booking['invoice_status'] = $invoice->status;
                                    switch ($invoice->status){
                                        case 'pending' :
                                            // the invoice is new and there was no payment tried for the amount
                                            $formatted_booking['button_finance'] = 'payment_btn_active';
                                            break;
                                        case 'ordered' :
                                            // we'll not use it
                                            break;
                                        case 'processing' :
                                            // payment was done successfully
                                        case 'completed' :
                                            // payment was done successfully
                                            switch($transaction->transaction_type){
                                                case 'cash' :
                                                    $formatted_booking['button_finance'] = 'is_paid_cash';
                                                    break;
                                                case 'card' :
                                                    $formatted_booking['button_finance'] = 'is_paid_card';
                                                    break;
                                                default :
                                                    $formatted_booking['button_finance'] = 'is_paid_online';
                                                    break;
                                            }
                                            break;
                                        case 'cancelled' :
                                            // invoice was canceled
                                            break;
                                        case 'declined' :
                                            // payment was declined
                                            $formatted_booking['button_finance'] = 'payment_btn_active';
                                            break;
                                        case 'incomplete' :
                                            // payment was incomplete
                                            $formatted_booking['button_finance'] = 'payment_issues';
                                            break;
                                        case 'preordered' :
                                            // we'll not use it
                                            break;
                                    }
                                }
                                else{
                                    $formatted_booking['button_finance'] = 'payment_btn_active';
                                }
                            }
                            break;
                    }
                }

                $booking_details[$booking->resource_id] = $formatted_booking;
                unset($transaction);
            }
        }

        //xdebug_var_dump($booking_details);
        return $booking_details;
    }

    /**
     * after the employee selects the boxes on the calendar and open the popup for booking details
     * the selected time intervals are saved and kept on the employee name until the booking is completed
     * @param Request $request
     * @return bool
     */
    public function calendar_booking_keep_selected(Request $request){
        if (Auth::check()) {
            $user = Auth::user();
        }
        else{
            return [];
        }

        $vars = $request->only('date','location','resources','time_interval','userID');
        if (isset($vars['userID']) && $vars['userID']!=-1){
            // search the user
            $bookingUser = User::find($vars['userID']);
            if (!$bookingUser){
                return [];
            }
        }
        else{
            $bookingUser = $user;
        }

        $location = ShopLocations::where('id','=',$vars['location'])->get()->first();
        if (!$location){
            return [];
        }

        $intervalDuration = 30;
        $selected_date = Carbon::createFromFormat('d-m-Y', trim($vars['date']))->format('Y-m-d');

        $booking_return = [];
        foreach ($vars['resources'] as $key=>$val){
            $booking_start_time = trim($vars['time_interval'][$key]);
            $booking_end_time   = Carbon::createFromFormat('G:i', $booking_start_time)->addMinutes($intervalDuration)->format('G:i');

            $fillable = [
                'by_user_id'            => $user->id,
                'for_user_id'           => $bookingUser->id,
                'location_id'           => $location->id,
                'resource_id'           => $val,
                'status'                => 'pending',
                'date_of_booking'       => $selected_date,
                'booking_time_start'    => $booking_start_time,
                'booking_time_stop'     => $booking_end_time,
                'payment_type'          => 'membership',
                'payment_amount'        => 0,
                'membership_id'         => -1,
                'invoice_id'            => -1,
                'search_key'            => '',
            ];
            $msg = $this->add_single_calendar_booking($fillable);
            $booking_return[] = $msg;
        }
        return $booking_return;
    }

    /**
     * Save the pre-kept booking with the player and member details;
     * Booking was kept on the employee name until the player/member were selected
     * @param Request $request
     * @return array
     */
    public function calendar_booking_save_selected(Request $request){
        if (!Auth::check()) {
            return [];
        }
        else{
            $user = Auth::user();
        }

        $vars = $request->only('book_key', 'by_player', 'for_player');

        // check if booking exists
        $booking = Booking::where('search_key','=',$vars['book_key'])->where('status','=','pending')->get()->first();
        if (!$booking){
            return [
                'success' => false,
                'errors' => 'No Booking found - booking expired'];
        }

        // check if the member that makes the booking exists
        if (!isset($vars['by_player'])){
            $by_user = $user;
        }
        else{
            $by_user = User::where('id','=',$vars['by_player'])->get()->first();
            if (!$by_user){
                return [
                    'success' => false,
                    'errors' => 'Member not found or can\'t have more bookings'];
            }
        }

        // check if the player that the booking is made for exists
        $for_user = User::where('id','=',$vars['for_player'])->get()->first();
        if (!$for_user){
            return [
                'success' => false,
                'errors' => 'Player not found or can\'t book on behalf of him'];
        }

        // check if the player can book
        $fillable = [
            'for_user_id' => $for_user->id,
            'resource_id' => $booking->resource_id,
            'location_id' => $booking->location_id,
            'date_of_booking' => $booking->date_of_booking,
            'booking_time_start' => $booking->booking_time_start,
            'search_key' => $booking->search_key
        ];
        $canBook = BookingController::validate_booking($fillable, $vars['book_key']);
        if ($canBook['status']==false){
            return ['booking_key' => ''];
        }
        else{
            $booking->payment_type = $canBook['payment'];

            $book_price = ShopResource::find($fillable['resource_id']);
            if ($book_price){
                $booking->payment_amount = $book_price->get_price($booking->date_of_booking, $booking->booking_time_start);
            }
            else{
                return ['booking_key' => ''];
            }
        }

        $booking->by_user_id = $by_user->id;
        $booking->for_user_id = $for_user->id;
        $booking->save();

        Activity::log([
            'contentId'     => $user->id,
            'contentType'   => 'bookings',
            'action'        => 'Save Selected Booking',
            'description'   => 'Set the player and the member that created the booking',
            'details'       => 'User Email : '.$user->email,
            'updated'       => true,
        ]);
        usleep(5000);

        return [
            'booking_key'   => $booking->search_key,
            'booking_type'  => $booking->payment_type,
            'booking_price' => $booking->payment_amount];
    }

    /**
     * Save all the pre-kept bookings with the player being the member; play alone option
     * @param Request $request
     * @return array
     */
    public function calendar_booking_save_play_alone(Request $request){
        if (!Auth::check()) {
            return [];
        }
        else{
            $user = Auth::user();
        }

        $vars = $request->only('book_keys', 'by_player', 'for_player');
        $return_bookings = [];
        $keys = explode(',',$vars['book_keys']);
        if (sizeof($keys)>0){
            foreach($keys as $key) {
                if ($key == '') {
                    continue;
                }

                // check if booking exists
                $booking = Booking::where('search_key','=',$key)->where('status','=','pending')->get()->first();
                if (!$booking){
                    $return_bookings[] = [
                        'booking_key'   => $key,
                        'error'         => true,
                        'message'       => 'Booking not found'];
                    continue;
                }
                // check if the member that makes the booking exists
                $by_user = User::where('id','=',$vars['by_player'])->get()->first();
                if (!$by_user){
                    return [
                        'success' => false,
                        'errors' => 'Member not found or can\'t have more bookings'];
                }

                // check if the player that the booking is made for exists
                $for_user = User::where('id','=',$vars['for_player'])->get()->first();
                if (!$for_user){
                    return [
                        'success' => false,
                        'errors' => 'Player not found or can\'t book on behalf of him'];
                }

                // check if the player can book
                $fillable = [
                    'for_user_id'   => $for_user->id,
                    'resource_id'   => $booking->resource_id,
                    'location_id'   => $booking->location_id,
                    'date_of_booking'    => $booking->date_of_booking,
                    'booking_time_start' => $booking->booking_time_start,
                    'search_key'    => $booking->search_key
                ];
                $canBook = BookingController::validate_booking($fillable, $key);

                if ($canBook['status']==false){
                    $return_bookings[] = [
                        'booking_key'   => $booking->search_key,
                        'error'         => true,
                        'message'       => 'Can\'t change player or can\'t book on the location/time you selected'];
                    continue;
                }
                else{
                    $booking->payment_type = $canBook['payment'];

                    $book_price = ShopResource::find($fillable['resource_id']);
                    if ($book_price){
                        $booking->payment_amount = $book_price->get_price($booking->date_of_booking, $booking->booking_time_start);
                    }
                    else{
                        $return_bookings[] = [
                            'booking_key'   => $booking->search_key,
                            'error'         => true,
                            'message'       => 'Error getting resource information!!!'];
                        continue;
                    }
                }

                if ($canBook['payment']=='cash'){
                    /*
                    // check for invoice
                    if (!isset($book_invoice)) {
                        // echo $booking->invoice_id;
                        if ($booking->invoice_id == -1) {
                            // add invoice
                            $book_invoice = $booking->add_invoice();
                        } else {
                            // get invoice
                            $book_invoice = BookingInvoice::where('id', '=', $booking->invoice_id)->get()->first();
                        }
                        //xdebug_var_dump($book_invoice);
                    }
                    $book_invoice->add_invoice_item($booking->id);
                    $booking->invoice_id = $book_invoice->id;
                    */
                }

                $booking->by_user_id = $by_user->id;
                $booking->for_user_id = $for_user->id;
                $booking->save();
                usleep(1000);

                Activity::log([
                    'contentId'     => $user->id,
                    'contentType'   => 'bookings',
                    'action'        => 'Save Booking',
                    'description'   => 'Calendar bookings play alone save',
                    'details'       => 'User Email : '.$user->email,
                    'updated'       => true,
                ]);

                $return_bookings[] = [
                    'booking_key'   => $booking->search_key,
                    'booking_type'  => $booking->payment_type,
                    'booking_price' => $booking->payment_amount
                ];
            }
        }
        else{
            $return_bookings = [
                'error' => true,
                'message' => 'No bookings transmitted'
            ];
        }

        return [ 'keys' => $return_bookings];
    }

    /**
     * Create a pending recurring calendar booking for each selected time period
     * @param Request $request
     * @return array
     */
    public function calendar_booking_save_recurring(Request $request){
        if (!Auth::check()) {
            return [];
        }
        else{
            $user = Auth::user();
        }

        $vars = $request->only('booking_key','occurrence','end_time','for_player','by_player');
        $keys = explode(',',$vars['booking_key']);
        $booking_return = [];

        if (sizeof($keys)>0) {
            foreach ($keys as $key) {
                if ($key == '') {
                    continue;
                }

                // check if booking exists
                $booking = Booking::where('search_key', '=', $key)->where('status', '=', 'pending')->get()->first();
                if (!$booking) {
                    $booking_return[] = [
                        'booking_key' => $key,
                        'error' => true,
                        'message' => 'Booking not found'];
                    continue;
                }
                else {
                    $booking->payment_type = 'recurring';
                    $booking->save();
                    $price_per_booking = $booking->payment_amount;

                    $booking_return[] = ['booking_key' => $booking->search_key,
                        'booking_date'  => Carbon::createFromFormat('Y-m-d',$booking->date_of_booking)->format('d-m-Y'),
                        'booking_time'  => Carbon::createFromFormat('G:i:s',$booking->booking_time_start)->format('G:i'),
                        'booking_resource'  => $booking->resource_id,
                        'is_alternative'    => -1,
                    ];
                }

                // check if the member that makes the booking exists
                $by_user = User::where('id', '=', $vars['by_player'])->get()->first();
                if (!$by_user) {
                    $booking_return[] = [
                        'booking_key'   => $key,
                        'success'       => false,
                        'errors'        => 'Member not found or can\'t have more bookings'];
                    continue;
                }

                // check if the player that the booking is made for exists
                $for_user = User::where('id', '=', $vars['for_player'])->get()->first();
                if (!$for_user) {
                    $booking_return[] = [
                        'booking_key'   => $key,
                        'success'       => false,
                        'errors' => 'Player not found or can\'t book on behalf of him'];
                    continue;
                }

                // interval duration
                $intervalDuration = 30;

                $booking->by_user_id = $by_user->id;
                $booking->for_user_id = $for_user->id;
                $booking->save();
                Activity::log([
                    'contentId'     => $user->id,
                    'contentType'   => 'bookings',
                    'action'        => 'Save Booking',
                    'description'   => 'save player booking recurring membership',
                    'details'       => 'User Email : '.$user->email,
                    'updated'       => true,
                ]);

                /*
                $theInvoice = BookingInvoice::where('id','=',$booking->invoice_id)->get()->first();
                if (!$theInvoice){
                    // create new invoice
                    $invoice = $booking->add_invoice();
                    if (isset($invoice->id)) {
                        $invoiceID = $invoice->id;
                        $theInvoice = BookingInvoice::where('id','=',$invoiceID)->get()->first();
                    }
                    else{
                        $booking_return[] = [
                            'booking_key'   => $key,
                            'success'       => false,
                            'errors'        => 'Could not generate invoice...'];
                        continue;
                    }
                }
                else{
                    $invoiceID = $theInvoice->id;
                }
                */

                $firstBookingDay = Carbon::createFromFormat('Y-m-d', $booking->date_of_booking);
                switch ($vars['occurrence']){
                    case 1 :
                        $nextBookingDay = $firstBookingDay->addDays(1);
                        break;
                    case 7 :
                        $nextBookingDay = $firstBookingDay->addDays(7);
                        break;
                    case 14:
                        $nextBookingDay = $firstBookingDay->addDays(14);
                        break;
                    case 21:
                        $nextBookingDay = $firstBookingDay->addDays(21);
                        break;
                    case 28:
                        $nextBookingDay = $firstBookingDay->addDays(28);
                        break;
                    default:
                        $nextBookingDay = $firstBookingDay->addDays(28);
                        break;
                }

                $booking_start_time = Carbon::createFromFormat('G:i:s', $booking->booking_time_start)->format('G:i');
                $booking_end_time   = Carbon::createFromFormat('G:i:s', $booking->booking_time_start)->addMinutes($intervalDuration)->format('G:i');
                $endDate = Carbon::createFromFormat('d-m-Y',$vars['end_time']);
                while ($endDate->diffInDays($nextBookingDay, false) < 0){
                    $fillable = [
                        'by_user_id'            => $by_user->id,
                        'for_user_id'           => $for_user->id,
                        'location_id'           => $booking->location_id,
                        'resource_id'           => $booking->resource_id,
                        'status'                => 'pending',
                        'date_of_booking'       => $nextBookingDay,
                        'booking_time_start'    => $booking_start_time,
                        'booking_time_stop'     => $booking_end_time,
                        'payment_type'          => 'recurring',
                        'payment_amount'        => $price_per_booking,
                        'membership_id'         => -1,
                        'invoice_id'            => -1,
                        'search_key'            => '',
                    ];

                    /*
                     * we check here if the automatic date/time calculated is good and can be booked,
                     * else we search for other resources in the same location at the same time
                     */
                    $tries = 1;
                    $valid_msg = $this->validate_booking($fillable,'',true);
                    while ( $valid_msg['status'] == false ){
                        $fillable = $this->get_booking_alternative($fillable);
                        $valid_msg = $this->validate_booking($fillable,'',true);
                        $tries++;
                        if ($tries>9){
                            break;
                        }
                    }

                    $msg = $this->add_single_calendar_booking($fillable, true);
                    if ($msg['booking_key']!='') {
                        $justBooked = Booking::where('search_key','=',$msg['booking_key'])->get()->first();

                        /*
                        $loc = ShopLocations::where('id','=',$justBooked->location_id)->get()->first();
                        $location_name = $loc->name;
                        $resource = ShopResource::where('id','=',$justBooked->resource_id)->get()->first();
                        $resource_name = $resource->name;
                        $booking_date = $justBooked->date_of_booking;
                        $booking_time_interval = $justBooked->booking_time_start.' - '.$justBooked->booking_time_stop;
                        $booking_price = $justBooked->payment_amount;
                        $vat = VatRate::orderBy('id','asc')->get()->first();
                        $vat_value = $vat->value;
                        $total_price = $booking_price + (($booking_price*$vat_value)/100);

                        if ($invoiceID!=-1){
                            /*$invoice_item_fill = [
                                'booking_invoice_id'=> $invoiceID,
                                'booking_id'        => $justBooked->id,
                                'location_name'     => $location_name,
                                'resource_name'     => $resource_name,
                                'quantity'          => 1,
                                'booking_date'      => $booking_date,
                                'booking_time_interval' => $booking_time_interval,
                                'price'             => $booking_price,
                                'vat'               => $vat_value,
                                'discount'          => 0,
                                'total_price'       => $total_price
                            ];
                            $theInvoice->add_invoice_item($justBooked->id);

                            Activity::log([
                                'contentId'     => $user->id,
                                'contentType'   => 'booking_invoices',
                                'action'        => 'Booking Invoice Update',
                                'description'   => 'New invoice item added to booking invoice',
                                'details'       => 'User Email : '.$user->email,
                                'updated'       => true,
                            ]);
                        }
                        */

                        $booking_return[] = ['booking_key' => $msg['booking_key'],
                            'booking_date'  => $nextBookingDay->format('d-m-Y'),
                            'booking_time'  => $booking_start_time,
                            'booking_resource'  => $booking->resource_id,
                            'is_alternative'    => $tries==1?0:1,
                        ];
                    }
                    else{
                        // error
                        $booking_return[] = ['booking_key' => $msg['booking_key'],
                            'booking_date'  => $nextBookingDay->format('d-m-Y'),
                            'booking_time'  => $booking_start_time,
                            'booking_resource'  => $booking->resource_id,
                            'is_alternative'    => -1
                        ];
                    }

                    switch ($vars['occurrence']){
                        case 1 :
                            $nextBookingDay = $firstBookingDay->addDays(1);
                            break;
                        case 7 :
                            $nextBookingDay = $firstBookingDay->addDays(7);
                            break;
                        case 14:
                            $nextBookingDay = $firstBookingDay->addDays(14);
                            break;
                        case 21:
                            $nextBookingDay = $firstBookingDay->addDays(21);
                            break;
                        case 28:
                            $nextBookingDay = $firstBookingDay->addDays(28);
                            break;
                        default:
                            $nextBookingDay = $firstBookingDay->addDays(28);
                            break;
                    }
                }
            }

            return ['keys' => $booking_return, 'status_msg' => 'all OK - '.implode('@#@#',$keys)];
        }
        else{
            return ['keys' => $booking_return, 'status_msg' => 'no keys found : '.$vars['booking_key']];
        }
    }

    /**
     * Create a single calendar booking using fillable
     * @param $fillable
     * @param bool $recurring
     * @return array
     */
    private function add_single_calendar_booking($fillable, $recurring = false){
        if (!Auth::check()) {
            return [];
        }
        else{
            $user = Auth::user();
        }

        $is_staff = false;
        if (!$user->hasRole(['front-member','front-user'])){
            $is_staff = true;
        }

        $search_key = Booking::new_search_key();
        $fillable['search_key'] = $search_key;

        $canBook = BookingController::validate_booking($fillable, $fillable['search_key'], $recurring, $is_staff);
        if ($canBook['status']==false){
            return ['booking_key' => '', 'error_msg' => 'booking date unavailable or outside membership range'];
        }
        else{
            $fillable['payment_type'] = $canBook['payment'];

            $book_price = ShopResource::find($fillable['resource_id']);
            if ($book_price){
                $fillable['payment_amount'] = $book_price->get_price($fillable['date_of_booking'], $fillable['booking_time_start']);
            }
            else{
                return ['booking_key' => '', 'error_msg' => 'could not determine book price'];
            }

            if ($fillable['payment_type']=="membership"){
                $membershipID = UserMembership::where('user_id','=',$fillable['for_user_id'])->where('status','=','active')->get()->first();
                if ($membershipID){
                    $fillable['membership_id'] = $membershipID->id;
                }
            }
        }

        $validator = Validator::make($fillable, Booking::rules('POST'), Booking::$message, Booking::$attributeNames);
        if ($validator->fails()){
            return array(
                'success' => false,
                'errors' => $validator->getMessageBag()->toArray()
            );
        }

        try {
            $the_booking = Booking::create($fillable);
            usleep(1000);

            Activity::log([
                'contentId'     => $user->id,
                'contentType'   => 'bookings',
                'action'        => 'New Booking',
                'description'   => 'New booking created',
                'details'       => 'User Email : '.$user->email,
                'updated'       => false,
            ]);

            /*
            if ($the_booking->payment_type=='cash' && $is_staff){
                $the_booking->add_invoice();

                Activity::log([
                    'contentId'     => $user->id,
                    'contentType'   => 'booking_invoices',
                    'action'        => 'New Booking Invoice',
                    'description'   => 'New booking invoice created for booking ID : '.$the_booking->id,
                    'details'       => 'User Email : '.$user->email,
                    'updated'       => false,
                ]);
            }
            */

            return [
                'booking_resource'  => $the_booking->resource_id,
                'booking_start_time'=> $the_booking->booking_time_start,
                'booking_key'       => $the_booking->search_key,
                'booking_type'      => $the_booking->payment_type,
                'booking_price'     => $the_booking->payment_amount];
        }
        catch (Exception $e) {
            return Response::json(['error' => 'Booking Error'], Response::HTTP_CONFLICT);
        }
    }

    /* Single Booking - quick actions START */

    /**
     * Action for show button on calendar view in admin panel (marks as show or active again)
     * @param Request $request
     * @return array success or error message
     */
    public function booking_action_player_show(Request $request){
        if (!Auth::check()) {
            return ['success'   => false,
                    'errors'    => 'You are not logged in or your login expired. Please login and return to this page',
                    'title'     => 'You need to login'];
        }
        else{
            $user = Auth::user();
        }

        $vars = $request->only('search_key');
        $booking = Booking::where('search_key','=',$vars['search_key'])->whereIn('status', ['active', 'paid', 'unpaid', 'old'])->get()->first();
        if ($booking){
            if ($booking->status == 'active'){
                if ($booking->payment_type=='cash'){
                    $invoice = BookingInvoice::find($booking->invoice_id);
                    if ($invoice && ($invoice->status=='processing' || $invoice->status=='completed')){
                        $booking->status = 'paid';
                    }
                    else{
                        $booking->status = 'unpaid';
                    }
                }
                else{
                    $booking->status = 'old';
                }
                $booking->save();

                return ['success' => true,
                        'title'   => 'Booking status changed',
                        'message' => 'Show status changed from "Active" to "Show"'];
            }
            else {
                $booking->status = 'active';
                $booking->save();

                return ['success' => true,
                        'title'   => 'Booking status changed',
                        'message' => 'Show status changed from "Show" to "Active"'];
            }
        }
        else {
            return ['success' => false,
                    'title'   => 'Booking not found',
                    'message' => 'Try reloading the page the change the status again'];
        }
    }

    /**
     * Adds a financial transaction to a booking and marks it as paid with cash/card/online
     * @param Request $request
     * @return array success or error
     */
    public function booking_action_pay_invoice(Request $request){
        if (!Auth::check()) {
            return [
                'success' => false,
                'errors' => 'You need to login first.'];
        }
        else{
            $user = Auth::user();
        }

        $vars = $request->only('search_key', 'method', 'status', 'other_details');
        $booking = Booking::where('search_key','=',$vars['search_key'])->whereIn('status', ['active', 'unpaid', 'old', 'noshow'])->get()->first();
        if ($booking && $booking->payment_type=='cash'){
            $invoice = BookingInvoice::find($booking->invoice_id);
            if (!$invoice){
                return [
                    'success' => false,
                    'errors' => 'Error Getting Invoice...'];
            }

            $invoiceItem = BookingInvoiceItem::where('booking_invoice_id','=',$invoice->id)->where('booking_id','=',$booking->id)->get()->first();
            if (!$invoiceItem){
                return [
                    'success' => false,
                    'errors'  => 'Error Creating Transaction! Please reload the page or try logout/login before trying the same action.'];
            }
            $totalAmount = $invoiceItem->total_price;

            if (!isset($vars['status'])){
                // manual payment at the store/location, so status is finished
                $vars['status'] = 'completed';
            }
            if (!isset($vars['other_details'])){
                // manual payment at the store/location, so status is finished
                $vars['other_details'] = 'Calendar view manual payment';
            }

            $message = $invoice->add_transaction($user->id, $invoiceItem->id, $totalAmount, $vars['method'], $vars['other_details'], $vars['status'] );
            if ($message['success']==true){
                // check if invoice is all paid
                $invoice->check_if_paid();

                Activity::log([
                    'contentId'     => $user->id,
                    'contentType'   => 'booking_invoices',
                    'action'        => 'Invoice transaction update',
                    'description'   => 'New transaction recorded for the invoice',
                    'details'       => 'User Email : '.$user->email,
                    'updated'       => true,
                ]);

                return [
                    'success' => true,
                    'message' => 'Transaction successfully registered.'];
            }
            else{
                return [
                    'success' => false,
                    'errors' => 'Error Creating Transaction! Please reload the page or try logout/login before trying the same action.'];
            }
        }
        else{
            return [
                'success' => false,
                'errors' => 'A membership booking can not be charged.'];
        }
    }

    /**
     * Get player statistics for booking more options popup window
     * @param Request $request
     * @return array
     */
    public function get_simple_player_statistics(Request $request){
        if (!Auth::check()) {
            return [
                'success' => false,
                'errors' => 'You need to login first.'];
        }
        else{
            $user = Auth::user();
        }

        $vars = $request->only('search_key');
        $booking = Booking::where('search_key','=',$vars['search_key'])->get()->first();
        if (!$booking){
            return [];
        }

        // bookings show : paid, unpaid, old
        $bshows = Booking::where('for_user_id','=',$booking->for_user_id)->whereIn('status',['paid','unpaid','old'])->count();

        // bookings no_show : no_show
        $bnoshows = Booking::where('for_user_id','=',$booking->for_user_id)->whereIn('status',['noshow'])->count();

        // bookings canceled : canceled
        $bcancel = Booking::where('for_user_id','=',$booking->for_user_id)->whereIn('status',['canceled'])->count();

        $stats = [
            'booking_show' => $bshows,
            'booking_no_show' => $bnoshows,
            'booking_cancel' => $bcancel,
            'player_about' => 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. ',
            'player_avatar' => asset('assets/pages/media/users/teambg3.jpg'),
        ];

        return $stats;
    }

    /**
     * Ongoing work
     * @param $playerID
     * @return array
     */
    public function get_player_statistics($playerID){
        if (!Auth::check()) {
            return [
                'success' => false,
                'errors' => 'You need to login first.'];
        }
        else{
            $user = Auth::user();
        }


    }

    /**
     * Used to get alternative bookings for recurring memberships
     * @param $fillable
     * @return mixed
     */
    public function get_booking_alternative($fillable){
        // try booking at the same hour/day in different resource room
        $resource = ShopResource::where('id','=',$fillable['resource_id'])->get()->first();
        $categoryID = $resource->category_id;

        $resources = ShopResource::where('location_id','=',$fillable['location_id'])->where('category_id','=',$categoryID)->get();
        if ($resources){
            foreach ($resources as $var) {
                $hasBooking = Booking::where('resource_id', '=', $var->id)->
                    where('date_of_booking','=',$fillable['date_of_booking'])->
                    where('booking_time_start','=',$fillable['booking_time_start'])->
                    whereNotIn('status',['canceled','expired'])->
                    get()->first();
                if (!$hasBooking){
                    $fillable['resource_id'] = $var->id;
                    return $fillable;
                }
            }
        }

        return $fillable;
    }
    /* Single Booking - quick actions END */

    /* Front-End controller functions - Start */
    public function front_bookings_archive(){
        $user = Auth::user();
        if (!$user || !$user->is_front_user()) {
            return redirect()->intended(route('homepage'));
        }

        $breadcrumbs = [
            'Home'      => route('admin'),
            'Dashboard' => '',
        ];
        $text_parts  = [
            'title'     => 'Home',
            'subtitle'  => 'users dashboard',
            'table_head_text1' => 'Dashboard Summary'
        ];
        $sidebar_link= 'front-bookings_list';

        return view('front/bookings/archive',[
            'breadcrumbs' => $breadcrumbs,
            'text_parts'  => $text_parts,
            'in_sidebar'  => $sidebar_link,
            'user'  => $user,
        ]);
    }

    /* Front-End - my bookings + archive */
    public function front_my_bookings(){
        if (Auth::check()) {
            $user = Auth::user();
        }
        else{
            return [];
        }

        $userID = $user->id;
        $bookingsList = [];
        $bookings = Booking::with('invoice')->where('for_user_id','=',$userID)
            ->orWhere('by_user_id','=',$userID)
            ->orderBy('date_of_booking','desc')
            ->orderBy('booking_time_start','desc')
            ->get();
        if (sizeof($bookings)>0){
            $buttons = [];
            $colorStatus = '';
            foreach ($bookings as $booking){
                $status = '';
                $format_date = Carbon::createFromFormat('Y-m-d H:i:s', $booking->date_of_booking.' '.$booking->booking_time_start);
                if ($booking->status != 'active'){
                    if ($booking->status=="unpaid" && $booking->status!="canceled"){
                        $bookingInvoice = $booking->invoice[0];
                        $generalInvoice = $bookingInvoice->get_general_invoice();
                        $status = '<a href="'.route('front/view_invoice/', ['id' => $generalInvoice->invoice_number]).'" class="btn btn-sm yellow-gold">Pay Invoice</a>';
                    }
                    elseif ($booking->status=="paid" && $booking->status!="canceled"){
                        $status = '<a href="#'.$booking->search_key.'" class="btn btn-sm green-turquoise">Invoice</a>';
                    }

                    if (sizeof($booking->notes)>0){
                        $status.= '<span data-id="' . $booking->search_key . '" class="details_booking btn btn-sm blue-sharp">Details</span> ';
                    }
                }
                else{
                    if ($this->can_cancel_booking($booking->id)) {
                        $status = '<span data-id="' . $booking->search_key . '" class="cancel_booking btn btn-sm grey-silver">Cancel</span> ';
                    }

                    if ($booking->by_user_id==$userID && Carbon::now()->lt($format_date)) {
                        $status.= ' <span data-id="' . $booking->search_key . '" class="modify_booking btn btn-sm blue-steel">Modify</span>';
                    }
                }

                switch ($booking->status) {
                    case 'pending' :
                        $colorStatus = 'warning';
                        $buttons = 'yellow-gold';
                        break;
                    case 'expired' :
                        $colorStatus = 'info';
                        $buttons = 'grey-salsa';
                        break;
                    case'active' :
                        $colorStatus = 'success';
                        $buttons = 'green-jungle';
                        break;
                    case 'paid' :
                        $colorStatus = 'success';
                        $buttons = 'green-jungle';
                        break;
                    case 'unpaid' :
                        $colorStatus = 'warning';
                        $buttons = 'yellow-gold';
                        break;
                    case 'noshow' :
                        $colorStatus = 'danger';
                        $buttons = 'red-thunderbird';
                        break;
                    case 'old' :
                        $colorStatus = 'info';
                        $buttons = 'green-meadow';
                        break;
                    case 'canceled' :
                        $colorStatus = 'warning';
                        $buttons = 'yellow-lemon';
                        break;
                }

                $date       = Carbon::createFromFormat('Y-m-d', $booking->date_of_booking)->format('l, M jS, Y');
                $addedOn    = Carbon::createFromFormat('Y-m-d H:i:s', $booking->updated_at)->format('j/m/Y');
                $dateSmall  = Carbon::createFromFormat('Y-m-d', $booking->date_of_booking)->format('j/m/Y');
                $timeInterval = Carbon::createFromFormat('H:i:s', $booking->booking_time_start)->format('H:i').' - '.Carbon::createFromFormat('H:i:s', $booking->booking_time_stop)->format('H:i');

                $madeForID = $booking->for_user_id;
                $userFor= User::find($booking->for_user_id);
                if ($userFor) {
                    $madeFor = $userFor->first_name . ' ' . $userFor->middle_name . ' ' . $userFor->last_name;
                }
                else{
                    $madeFor = ' - ';
                }

                $location = ShopLocations::find($booking->location_id);
                $locationName = $location->name;
                $room = ShopResource::find($booking->resource_id);
                $roomName = $room->name;
                $category = ShopResourceCategory::find($room->category_id);
                $categoryName = $category->name;

                $bookingsList[] = [
                    'dateShort'     => $dateSmall,
                    'date'          => $date,
                    'timeInterval'  => $timeInterval,
                    'player_name'   => $madeFor,
                    'player_id'     => $madeForID,
                    'location'      => $locationName,
                    'room'          => $roomName,
                    'activity'      => $categoryName,
                    'status'        => $booking->status,
                    'color_status'  => $colorStatus,
                    'color_button'  => $buttons,
                    'last_update'   => $booking->updated_at,
                    'added_by'      => $booking->by_user_id,
                    'search_key'    => $booking->search_key,
                    'added_on'      => $addedOn,
                    'button_actions'=> @$status
                ];
            }
//xdebug_var_dump($bookingsList); exit;
            unset($bookings); unset($booking);

            $nr = 1;
            $index = [];
            $lastMan = 0;
            $lastTime = '';
            $lastTenBookings = [];
            foreach($bookingsList as $key=>$lastOne){
                if ($lastOne['status']!='active'){
                    continue;
                }
                if (!isset($lastMan)){ $lastMan = $lastOne['added_by']; }
                if (!isset($lastTime)){ $lastTime = $lastOne['last_update']; }

                if ($lastMan==$lastOne['added_by'] && $lastTime==$lastOne['last_update']){
                    $nr++;
                    $lastOne['colspan'] = 0;
                }
                else{
                    $lastMan  = $lastOne['added_by'];
                    $lastTime = $lastOne['last_update'];

                    $indexKey = sizeof($index)+1;
                    $index[$indexKey-1] = $nr;

                    if ($indexKey>50){
                        break;
                    }
                    $nr=1;
                }

                switch ($lastOne['status']) {
                    case 'pending' :
                        $colorStatus = 'label-warning';
                        break;
                    case 'expired' :
                        $colorStatus = 'label-default';
                        break;
                    case 'active' :
                        $colorStatus = 'label-success';
                        break;
                    case 'paid' :
                        $colorStatus = 'label-success';
                        break;
                    case 'unpaid' :
                        $colorStatus = 'label-danger';
                        break;
                    case 'noshow' :
                        $colorStatus = 'label-danger';
                        break;
                    case 'old' :
                        $colorStatus = 'label-info';
                        break;
                    case 'canceled' :
                        $colorStatus = 'label-default';
                        break;
                }
                $lastOne['status-color'] = $colorStatus;
                $lastTenBookings[] = $lastOne;

                unset($bookingsList[$key]);
            }
            if (sizeof($lastTenBookings)>0){
                $index[$indexKey] = $nr;
            }
        }

        $breadcrumbs = [
            'Home'      => route('admin'),
            'Dashboard' => '',
        ];
        $text_parts  = [
            'title'     => 'Home',
            'subtitle'  => 'users dashboard',
            'table_head_text1' => 'Dashboard Summary'
        ];
        $sidebar_link= 'front-bookings_list';

        return view('front/bookings/my_bookings',[
            'breadcrumbs' => $breadcrumbs,
            'text_parts'  => $text_parts,
            'in_sidebar'  => $sidebar_link,
            'user'  => $user,
            'bookings'    => $bookingsList,
            'multipleBookingsIndex' => isset($index)?$index:[],
            'lastTen' =>  isset($lastTenBookings)?$lastTenBookings:[]
        ]);
    }

    /**
     * Booking calendar - Front member view
     * @param int $date_selected
     * @param int $selected_location
     * @param int $selected_activity
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function front_bookings_calendar_view($date_selected = 0, $selected_location = 0, $selected_activity = 0){
        $user = Auth::user();
        if (!$user || !$user->is_front_user()) {
            return redirect()->intended(route('homepage'));
        }

        $settings = UserSettings::get_general_settings($user->id, ['settings_preferred_location','settings_preferred_activity']);

        // Date validation and variables assignation
        if ($date_selected==0){
            $date_selected = Carbon::now()->format('Y-m-d');
        }
        else{
            $date_selected = Carbon::createFromFormat('d-m-Y',$date_selected)->format('Y-m-d');

            // check if date_selected is less than today
            $selected = Carbon::createFromFormat('Y-m-d',$date_selected);
            if ($selected->lt(Carbon::today())){
                return redirect()->intended(route('front_calendar_booking', ['day' => Carbon::today()->format('d-m-Y')]));
            }
            elseif ($selected->gte(Carbon::today()->addDays(7))){
                return redirect()->intended(route('front_calendar_booking', ['day' => Carbon::today()->addDays(7)->format('d-m-Y')]));
            }
        }
        $header_vals['date_selected'] = Carbon::createFromFormat('Y-m-d',$date_selected)->format('d-m-Y');
        $header_vals['next_date'] = Carbon::createFromFormat('Y-m-d',$date_selected)->addDay(1)->format('d-m-Y');
        $header_vals['prev_date'] = Carbon::createFromFormat('Y-m-d',$date_selected)->addDay(-1)->format('d-m-Y');

        // location validation and variables assignation
        if ($selected_location==0){
            $default_location = isset($settings['settings_preferred_location'])?$settings['settings_preferred_location']:7;
        }
        else{
            $default_location = $selected_location;
        }
        $location_found = false;
        $all_locations = ShopLocations::select('id','name')->where('visibility','=','public')->orderBy('name','ASC')->get();
        foreach ($all_locations as $location){
            if ($location->id==$default_location){
                $location_found=true;
            }
        }
        if ($location_found==false){
            // redirect not found
            $default_location = 7;
        }
        unset($location);
        $header_vals['selected_location'] = $default_location;

        $location = ShopLocations::select('id','name')->where('id','=',$default_location)->get()->first();

        // activity/category validation and variables assignation
        $locationActivities = [];
        $checkLocations = [];
        $first_line_activity = -1;
        $availableActivities = ShopResource::with('category')->where('location_id','=',$location->id)->groupBy('category_id')->get();
        if ($availableActivities){
            foreach($availableActivities as $oneActivity){
                if ($first_line_activity == -1){
                    $first_line_activity = $oneActivity->category_id;
                }
                $locationActivities[] = ['id' => $oneActivity->category_id, 'name' => $oneActivity->category->name];
                $checkLocations[] = $oneActivity->category_id;
            }
        }

        if ($selected_activity==0){
            $default_activity = isset($settings['settings_preferred_activity'])?$settings['settings_preferred_activity']:$first_line_activity;
        }
        else{
            $default_activity = $selected_activity;
        }

        if (!in_array($default_activity, $checkLocations)){
            // redirect to default first activity in the location
            return redirect()->intended(route('front_calendar_booking_all',['day'=>$header_vals['date_selected'], 'location'=>$location->id, 'activity'=>$first_line_activity]));
        }

        unset($activity);
        $header_vals['selected_activity'] = $default_activity;

        $activity = ShopResourceCategory::where('id','=',$default_activity)->get()->first();

        $resources_ids = [];
        $resources = ShopResource::where('location_id','=',$location->id)->where('category_id','=',$activity->id)->get();
        if ($resources){
            foreach($resources as $resource){
                $resources_ids[] = $resource->id;
            }
        }

        $locationOpeningHours = $location->get_opening_hours($date_selected);
        if (isset($locationOpeningHours['open_at']) && isset($locationOpeningHours['close_at'])){
            $open_at  = $locationOpeningHours['open_at'];
            $close_at = $locationOpeningHours['close_at'];
        }
        else{
            $open_at  = '07:00';
            $close_at = '23:00';
        }

        $hours_interval = $this->make_hours_interval($date_selected, $open_at, $close_at, 30, false, false);
        $location_bookings = $this->player_get_location_bookings($date_selected, $location->id, $resources_ids, $hours_interval);

        $resources_ids = [];
        foreach($resources as $resource){
            $resources_ids[] = ['name'=>$resource->name, 'id'=>$resource->id];
        }

        $breadcrumbs = [
            'Home'              => route('admin'),
            'Administration'    => route('admin'),
            'Back End Users'    => route('admin/back_users'),
        ];
        $sidebar_link= 'admin-frontend-user_details_view';
        return view('front/booking_calendar', [
            'breadcrumbs'   => $breadcrumbs,
            'in_sidebar'    => $sidebar_link,
            'time_intervals'    => $hours_interval,
            'location_bookings' => $location_bookings['hours'],
            'resources'     => $resources_ids,
            'header_vals'   => $header_vals,
            'all_locations' => $all_locations,
            'all_activities'=> $locationActivities,
            //'all_activities'=> $all_activities,
        ]);
    }

    /**
     * Player front view - Get location bookings based on location, resources, time intervals
     * @param $date_selected - date variable in dd-mm-yyyy format
     * @param $location - array of IDs or single id for ShopLocations
     * @param $resources - array of IDs or id for shopResources
     * @param $hours - array of hours interval (start of booking times)
     * @return array - returns 'hours' array and 'bookings' array
     *               - hours - intervals with colors for booked or free colors
     *               - bookings - individual bookings for time interval keys
     */
    public function player_get_location_bookings($date_selected, $location, $resources, $hours){
        if (Auth::check()) {
            $user = Auth::user();
            BookingController::check_for_expired_pending_bookings();
        }

        $bookings = [];
        // check if we get today or 10 days from today
        $dateSelected = Carbon::createFromFormat("Y-m-d", $date_selected);
        if (!$dateSelected){
            return [];
        }

        foreach($hours as $key=>$hour){
            $hours[$key] = $this->player_check_date_time_bookings($date_selected, $key, $location, $resources, false);
        }

        $returnArray = ["hours"=>$hours, "bookings"=>$bookings];

        return $returnArray;
    }

    /**
     * Player front view - Returns bookings for specified data and time for a location's/resource's
     * @param $date = date in dd-mm-yyyy format
     * @param $time = start time of booking in hh:mm format
     * @param $location = array of ShopLocation IDs or single id
     * @param $resource = array of ShopResource IDs or single id
     * @param bool $show_more = shows more information on the admin calendar view
     * @return array = array of bookings for data/time/resource specified at the beginning
     */
    public function player_check_date_time_bookings($date, $time, $location, $resource, $show_more=false){
        $booking_details = [];
        $buttons_color = [
            'is_show'           => '',
            'is_no_show'        => '',
            'show_btn_active'   => '',
            'is_paid_cash'      => '',
            'is_paid_card'      => '',
            'is_paid_online'    => '',
            'payment_issues'    => '',
            'payment_btn_active'=> '',
            'more_btn_active'   => '',
            'is_disabled'       => '',
        ];

        $q = DB::table('bookings');
        if (is_array($location)){
            $q->whereIn('location_id', $location);
        }
        else{
            $q->where('location_id','=',$location);
        }

        if (is_array($resource)){
            $q->whereIn('resource_id', $resource);
        }
        else{
            $q->where('resource_id','=',$resource);
        }
        $q->where('date_of_booking','=',$date)
            ->whereIn('status',['pending','active','paid','unpaid','old','noshow'])
            ->where('booking_time_start','=',$time);
        $bookings = $q->get();

        if ($bookings){
            foreach ($bookings as $booking){
                $formatted_booking = [
                    'search_key' => $booking->search_key,
                    'location' => $booking->location_id,
                    'resource' => $booking->resource_id,
                    'status'   => $booking->status,
                    'color_stripe' => 'bg-red-flamingo bg-font-red-flamingo'];

                if ($show_more){
                    $player = User::find($booking->for_user_id);
                    $formatted_booking['payment_type'] = $booking->payment_type;
                    $formatted_booking['payment_amount'] = $booking->payment_amount;
                    $formatted_booking['invoice'] = $booking->invoice_id;
                    $formatted_booking['player_name'] = $player->first_name.' '.$player->middle_name.' '.$player->last_name;
                    $formatted_booking['id'] = $booking->id;

                    if ($formatted_booking['payment_type'] == 'membership' && $booking->status!='pending'){
                        $formatted_booking['color_stripe'] = 'bg-green-haze bg-font-green-haze';
                    }
                    elseif ($formatted_booking['payment_type'] == 'cash' && $booking->status!='pending'){
                        $formatted_booking['color_stripe'] = 'bg-yellow-gold bg-font-yellow-gold';
                    }
                    elseif ($formatted_booking['payment_type'] == 'recurring' && $booking->status!='pending'){
                        $formatted_booking['color_stripe'] = 'bg-purple-wisteria bg-font-purple-wisteria';
                    }
                    elseif($booking->status=='pending'){
                        $formatted_booking['color_stripe'] = 'bg-yellow-soft bg-font-yellow-soft';
                    }

                    $formatted_booking['button_show'] = 'is_disabled';
                    $formatted_booking['button_finance'] = 'is_disabled';
                    $formatted_booking['button_more'] = 'more_btn_active';
                    switch ($booking->status) {
                        case 'pending' :
                            // all disabled
                            $formatted_booking['button_more'] = 'is_disabled';
                            break;
                        case 'active' :
                            $formatted_booking['button_show'] = 'show_btn_active';
                            if ($booking->payment_type=="cash"){
                                $invoice = BookingInvoice::with('financial_transaction')->find($booking->invoice_id);
                                if ($invoice){
                                    foreach ($invoice->financial_transaction as $ab) {
                                        $transaction = $ab;
                                        break;
                                    }

                                    $formatted_booking['invoice_status'] = $invoice->status;
                                    switch ($invoice->status){
                                        case 'pending' :
                                            // the invoice is new and there was no payment tried for the amount
                                            $formatted_booking['button_finance'] = 'payment_btn_active';
                                            break;
                                        case 'ordered' :
                                            // we'll not use it
                                            break;
                                        case 'processing' :
                                            // a payment process started and the result is waiting
                                        case 'completed' :
                                            // payment was done successfully
                                            switch($transaction->transaction_type){
                                                case 'cash' :
                                                    $formatted_booking['button_finance'] = 'is_paid_cash';
                                                    break;
                                                case 'card' :
                                                    $formatted_booking['button_finance'] = 'is_paid_card';
                                                    break;
                                                default :
                                                    $formatted_booking['button_finance'] = 'is_paid_online';
                                                    break;
                                            }
                                            break;
                                        case 'cancelled' :
                                            // invoice was canceled
                                            break;
                                        case 'declined' :
                                            // payment was declined
                                            $formatted_booking['button_finance'] = 'payment_btn_active';
                                            break;
                                        case 'incomplete' :
                                            // payment was incomplete
                                            $formatted_booking['button_finance'] = 'payment_issues';
                                            break;
                                        case 'preordered' :
                                            // we'll not use it
                                            break;
                                    }
                                }
                                else{
                                    // no invoice so the button is active and can be paid
                                    $formatted_booking['button_finance'] = 'payment_btn_active';
                                }
                            }
                            break;
                        case 'paid'   :
                        case 'unpaid' :
                            // show button was clicked
                            $formatted_booking['button_show'] = 'is_show';
                            $invoice = BookingInvoice::with('financial_transaction')->find($booking->invoice_id);
                            if ($invoice){
                                foreach ($invoice->financial_transaction as $ab) {
                                    $transaction = $ab;
                                    break;
                                }

                                $formatted_booking['invoice_status'] = $invoice->status;
                                switch ($invoice->status){
                                    case 'pending' :
                                        // the invoice is new and there was no payment tried for the amount
                                        $formatted_booking['button_finance'] = 'payment_btn_active';
                                        break;
                                    case 'ordered' :
                                        // we'll not use it
                                        break;
                                    case 'processing' :
                                        // payment was done successfully
                                    case 'completed' :
                                        // payment was done successfully
                                        switch($transaction->transaction_type){
                                            case 'cash' :
                                                $formatted_booking['button_finance'] = 'is_paid_cash';
                                                break;
                                            case 'card' :
                                                $formatted_booking['button_finance'] = 'is_paid_card';
                                                break;
                                            default :
                                                $formatted_booking['button_finance'] = 'is_paid_online';
                                                break;
                                        }
                                        break;
                                    case 'cancelled' :
                                        // invoice was canceled
                                        break;
                                    case 'declined' :
                                        // payment was declined
                                        $formatted_booking['button_finance'] = 'payment_btn_active';
                                        break;
                                    case 'incomplete' :
                                        // payment was incomplete
                                        $formatted_booking['button_finance'] = 'payment_issues';
                                        break;
                                    case 'preordered' :
                                        // we'll not use it
                                        break;
                                }
                            }
                            $formatted_booking['invoice_status'] = 'completed';
                            break;
                        case 'unpaid2' :
                            // show button was clicked
                            $formatted_booking['button_show'] = 'is_show';
                            $formatted_booking['invoice_status'] = 'pending';
                            break;
                        case 'old' :
                            // show button was clicked, player is show
                            $formatted_booking['button_show'] = 'is_show';
                            break;
                        case 'noshow' :
                            // the player is no show
                            $formatted_booking['button_show'] = 'is_no_show';
                            if ($booking->payment_type=="cash"){
                                $invoice = BookingInvoice::find($booking->invoice_id);
                                if ($invoice){
                                    foreach ($invoice->financial_transaction as $ab) {
                                        $transaction = $ab;
                                        break;
                                    }

                                    $formatted_booking['invoice_status'] = $invoice->status;
                                    switch ($invoice->status){
                                        case 'pending' :
                                            // the invoice is new and there was no payment tried for the amount
                                            $formatted_booking['button_finance'] = 'payment_btn_active';
                                            break;
                                        case 'ordered' :
                                            // we'll not use it
                                            break;
                                        case 'processing' :
                                            // payment was done successfully
                                        case 'completed' :
                                            // payment was done successfully
                                            switch($transaction->transaction_type){
                                                case 'cash' :
                                                    $formatted_booking['button_finance'] = 'is_paid_cash';
                                                    break;
                                                case 'card' :
                                                    $formatted_booking['button_finance'] = 'is_paid_card';
                                                    break;
                                                default :
                                                    $formatted_booking['button_finance'] = 'is_paid_online';
                                                    break;
                                            }
                                            break;
                                        case 'cancelled' :
                                            // invoice was canceled
                                            break;
                                        case 'declined' :
                                            // payment was declined
                                            $formatted_booking['button_finance'] = 'payment_btn_active';
                                            break;
                                        case 'incomplete' :
                                            // payment was incomplete
                                            $formatted_booking['button_finance'] = 'payment_issues';
                                            break;
                                        case 'preordered' :
                                            // we'll not use it
                                            break;
                                    }
                                }
                                else{
                                    $formatted_booking['button_finance'] = 'payment_btn_active';
                                }
                            }
                            break;
                    }
                }

                $booking_details[$booking->resource_id] = $formatted_booking;
                unset($transaction);
            }
        }
        return $booking_details;
    }

    public function single_booking_details(Request $request){
        $bookingDetails = [];

        if (Auth::check()) {
            $user = Auth::user();
            $is_backend_employee = $user->can('booking-change-update');
        }
        else{
            return $bookingDetails;
        }

        $vars = $request->only('search_key','the_user');
        $booking = Booking::with('notes')->where('search_key','=',$vars['search_key'])->get()->first();
        if ($booking){
            // the logged in user is : the player or the person that did the booking or an employee with booking edit options
            if ( $user->id!=$booking->for_user_id && $user->id!=$booking->by_user_id && $is_backend_employee == false ){
                return $bookingDetails;
            }
            $bookingDetails = $booking->get_summary_details($is_backend_employee);
            /*$canCancel  = '0';
            $canModify  = '0';
            $invoiceLink = '0';
            $noShow = '0';
            if ($user->can('booking-change-update')){
                switch ($booking->status) {
                    case'active' :
                        $canCancel  = '1';
                        $canModify  = '1';
                        // if time of booking is less than current time + a time limit of x days
                        $noShow = '1';
                        break;
                    case 'paid' :
                        $invoiceLink = '1';
                        // if time of booking is less than current time + a time limit of x days
                        $noShow = '1';
                        break;
                    case 'unpaid' :
                        $canModify  = '1';
                        $invoiceLink = '1';
                        // if time of booking is less than current time + a time limit of x days
                        $noShow = '1';
                        break;
                    case 'noshow' :
                        $invoiceLink = '1';
                        break;
                    case 'pending' :
                    case 'expired' :
                    case 'old' :
                    case 'canceled' :
                    default:
                        break;
                }
            }

            $location = ShopLocations::find($booking->location_id);
            $locationName = $location->name;
            $room = ShopResource::find($booking->resource_id);
            $roomName = $room->name;
            $roomPrice= $room->session_price;
            $category = ShopResourceCategory::find($room->category_id);
            $categoryName = $category->name;

            $userBy = User::find($booking->by_user_id);
            if ($userBy) {
                $madeBy = $userBy->first_name . ' ' . $userBy->middle_name . ' ' . $userBy->last_name;
            }
            $userFor= User::find($booking->for_user_id);
            if ($userFor) {
                $madeFor = $userFor->first_name . ' ' . $userFor->middle_name . ' ' . $userFor->last_name;
            }

            if ($booking->payment_type == 'cash'){
                $financeDetails = ' Payment of '.$booking->payment_amount;
            }
            else{
                $financeDetails = "Membership included";
            }

            $allNotes = [];
            if (sizeof($booking->notes)>0){
                foreach($booking->notes as $note){
                    $a_note = [];
                    if ($note->privacy!='everyone' && $is_backend_employee==false ){
                        continue;
                    }
                    if ($note->privacy!='admin' && $note->status=='deleted'){
                        continue;
                    }

                    $a_note['note_title'] = $note->note_title;
                    $a_note['note_body']  = $note->note_body;
                    $a_note['created_at'] = Carbon::createFromFormat('Y-m-d H:i:s', $note->created_at)->format('H:s - M jS, Y');

                    $notePerson = User::select('first_name','middle_name','last_name')->where('id','=',$note->by_user_id)->get()->first();
                    $a_note['added_by'] = $notePerson->first_name.' '.$notePerson->middle_name.' '.$notePerson->last_name;

                    $allNotes[] = $a_note;
                }
            }

            $bookingDetails = [
                'bookingDate'   => Carbon::createFromFormat('Y-m-d', $booking->date_of_booking)->format('l, M jS, Y'),
                'timeStart'     => Carbon::createFromFormat('H:i:s', $booking->booking_time_start)->format('H:i'),
                'timeStop'      => Carbon::createFromFormat('H:i:s', $booking->booking_time_stop)->format('H:i'),
                'paymentType'   => $booking->payment_type,
                'paymentAmount' => $booking->payment_amount,
                'financialDetails' => $financeDetails,
                'location'      => $locationName,
                'room'          => $roomName,
                'roomPrice'     => $roomPrice,
                'category'      => $categoryName,
                'byUserName'    => @$madeBy,
                'forUserName'   => @$madeFor,
                'forUserID'     => @$booking->for_user_id,
                'canCancel'     => $canCancel,
                'canModify'     => $canModify,
                'invoiceLink'   => $invoiceLink,
                'canNoShow'     => $noShow,
                'bookingNotes'  => $allNotes,
            ];*/

            return $bookingDetails;
        }
        else{
            return $bookingDetails;
        }
    }
    /* Front-End controller functions - Stop */
}