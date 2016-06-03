<?php

namespace App\Http\Controllers;

use App\Booking;
use App\BookingInvoice;
use App\ShopLocations;
use App\ShopResource;
use App\ShopResourceCategory;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;
use Validator;
use Zizaco\Entrust\EntrustRole;
use Auth;
use Carbon\Carbon;
use DateInterval;
use DatePeriod;
use DB;

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

        /** @var  $vars */
        $vars = $request->only('selected_activity', 'selected_date', 'selected_location', 'selected_payment', 'selected_resource', 'selected_time', 'book_key', 'player');
        $search_key = substr( base64_encode(openssl_random_pseudo_bytes(32)),0 ,63 );

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
            'invoice_id'    => 1,
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
                $fillable['payment_amount'] = $book_price->session_price;
            }
            else{
                return ['booking_key' => ''];
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
                Booking::create($fillable);
            }
            else{
                $the_booking = Booking::where('search_key', '=', $vars['book_key'])->get()->first();
                if ($the_booking) {
                    $fillable['search_key'] = $vars['book_key'];
                    $the_booking->fill($fillable);
                    $the_booking->save();
                }
                else{
                    $search_key = $vars['book_key'];
                }
            }

            return ['booking_key' => $search_key, 'booking_type' => $fillable['payment_type'], 'booking_price' => $fillable['payment_amount']];
        } catch (Exception $e) {
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

    public function cancel_booking(Request $request){
        if (!Auth::check()) {
            //return redirect()->intended(route('admin/login'));
            return ['error' => 'Authentication Error'];
        }
        else{
            $user = Auth::user();
        }

        $vars = $request->only('search_key');
        if ($user->can('booking-change-update')){
            $booking = Booking::where('search_key','=',$vars['search_key'])->get()->first();
        }
        else {
            $booking = Booking::where('for_user_id','=',$user->id)->orWhere('by_user_id','=',$user->id)->where('search_key','=',$vars['search_key'])->get()->first();
        }

        if ($booking){
            if (in_array($booking->status,['active','pending'])){
                $booking->status = 'canceled';
                $booking->save();
            }
            //Booking::whereIn('status',['active', 'pending'])->where('search_key','=',$vars['search_key'])->update(['status'=>'canceled']);
            return ['success' => 'true', 'message' => 'All is good.'];
        }
        else{
            return ['error' => 'No bookings found to confirm. Please make the booking process again. Remember you have 60 seconds to complete the booking before it expires.'];
        }
    }

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

    private function validate_booking($fillable, $search_key=''){
        $message = ['status'=>true, 'payment'=>'membership'];
        if (Auth::check()) {
            $user = Auth::user();
        }

        // check for open bookings
        $ownBookings = Booking::whereIn('status',['pending','active'])
            ->where('for_user_id','=',$fillable['for_user_id'])
            ->where('search_key','!=',$search_key)
            ->get();
        if (sizeof($ownBookings)==0){
            // no open bookings except the search key
            $message['payment'] = 'membership';
        }
        else if (sizeof($ownBookings)>0 && @$user->id==$fillable['for_user_id']){
            // more than the current pending booking and the user is the logged in user
            $message['payment'] = 'cash';
        }
        else{
            // at least one booking found and the user is not the logged in one
            $message['status'] = false;
            return $message;
        }

        // check for existing booking on the same resurce
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

    public static function check_for_expired_pending_bookings($time = 60){
        Booking::where('updated_at','<',Carbon::now()->subSeconds($time))->where('status','=','pending')->update(['status'=>'expired']);
    }

    /*
     *
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

    public function bookings_summary(Request $request){
        if (!Auth::check()) {
            //return redirect()->intended(route('admin/login'));
            return ['error' => 'Authentication Error'];
        }
        else{
            $user = Auth::user();
        }

        $this->check_for_expired_pending_bookings();

        $vars = $request->only('all_bookings');
        $keys = explode(',',$vars['all_bookings']);
        $membership_nr = 0;
        $cash_nr = 0;
        $cash_amount = 0;

        if (sizeof($keys)>0){
            foreach($keys as $key){
                if ($key==''){ continue; }
                $booking = Booking::where('by_user_id','=',$user->id)
                            ->where('search_key','=',$key)
                            ->get()->first();

                if ($booking){
                    //xdebug_var_dump($booking);
                    if ($booking['payment_type']=='cash'){
                        $cash_nr+=1;
                        $cash_amount+= $booking['payment_amount'];
                    }
                    else{
                        $membership_nr+=1;
                    }
                }
            }

            return ['success' => 'true', 'membership_nr' => $membership_nr, 'cash_nr' => $cash_nr, 'cash_amount' => $cash_amount.' NOK' ];
        }
        else{
            return ['error' => 'No bookings found to confirm. Please make the booking process again. Remember you have 60 seconds to complete the booking before it expires.'];
        }
    }

    public function confirm_bookings(Request $request){
        if (!Auth::check()) {
            //return redirect()->intended(route('admin/login'));
            return ['error' => 'Authentication Error'];
        }
        else{
            $user = Auth::user();
        }

        $this->check_for_expired_pending_bookings();

        $vars = $request->only('selected_bookings');
        $keys = explode(',',$vars['selected_bookings']);

        if (sizeof($keys)>0){
            foreach($keys as $key){
                if ($key==''){ continue; }
                Booking::where('status','=','pending')->where('by_user_id','=',$user->id)->where('search_key','=',$key)->update(['status'=>'active']);
            }
        }
        else{
            return ['error' => 'No bookings found to confirm. Please make the booking process again. Remember you have 60 seconds to complete the booking before it expires.'];
        }

        return 'bine';
    }

    public function cancel_bookings(Request $request){
        if (!Auth::check()) {
            //return redirect()->intended(route('admin/login'));
            return ['error' => 'Authentication Error'];
        }
        else{
            $user = Auth::user();
        }

        $this->check_for_expired_pending_bookings();

        $vars = $request->only('selected_bookings');
        $keys = explode(',',$vars['selected_bookings']);
        if (sizeof($keys)>0){
            foreach($keys as $key){
                if ($key==''){ continue; }
                Booking::where('status','=','pending')->where('by_user_id','=',$user->id)->where('search_key','=',$key)->update(['status'=>'canceled']);
            }

            return ['success' => 'true', 'message' => 'All is good.'];
        }
        else{
            return ['error' => 'No bookings found to confirm. Please make the booking process again. Remember you have 60 seconds to complete the booking before it expires.'];
        }
    }

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

        $allBookings = Booking::with('notes')->where('by_user_id','=',$userID)->orWhere('for_user_id','=',$userID)->whereNotIn('status',['expired'])->get();
        if ($allBookings){
            foreach($allBookings as $booking){
                $format_date = Carbon::createFromFormat('Y-m-d H:i:s', $booking->date_of_booking.' '.$booking->booking_time_start)->format('Y,M j, H:i');
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

                    if ($booking->by_user_id==$userID) {
                        $status.= ' <span data-id="' . $booking->search_key . '" class="modify_booking btn blue-steel">Modify</span>';
                    }
                }

                $data[] = [
                    $format_date,
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

    public function can_cancel_booking($id, $hours = 8){
        $booking = Booking::find($id); //exit;
        //xdebug_var_dump($booking);
        if ($booking){
            $bookingStartDateTime = Carbon::createFromFormat('Y-m-d H:i:s', $booking->date_of_booking.' '.$booking->booking_time_start);
            $actualTime = Carbon::now()->addHours($hours);

            if ($bookingStartDateTime->gte($actualTime)){
                return true;
            }
            else{
                return false;
            }
        }
        else{
            return false;
        }
    }

// This was moved to booking controller
/*
    public function add_invoice_to_booking($fillable, $booking){
        if (!Auth::check()) {
            //return redirect()->intended(route('admin/login'));
            return ['error' => 'Authentication Error'];
        }
        else{
            $user = Auth::user();
        }

        $vars = $request->only('search_key', 'status');
        $booking = Booking::where('search_key','=',$vars['search_key'])->get()->first();
        if ($booking){
            $booking->add_invoice();

            $booking->status = $vars['status'];
            $booking->save();

            //Booking::where('search_key','=',$vars['search_key'])->update(['status'=>$vars['status']]);
            return ['success' => 'true', 'message' => 'All is good.'];
        }
        else{
            return ['error' => 'No bookings found to confirm. Please make the booking process again. Remember you have 60 seconds to complete the booking before it expires.'];
        }

        return 'bine';
    }

    public function add_note_to_booking($fillable, $booking){
        if (!Auth::check()) {
            //return redirect()->intended(route('admin/login'));
            return ['error' => 'Authentication Error'];
        }
        else{
            $user = Auth::user();
        }

        $vars = $request->only('search_key', 'default_player_messages', 'custom_player_message', 'private_player_message');
        $booking = Booking::where('search_key','=',$vars['search_key'])->get()->first();
        if ($booking){
            $fillable = [
                'by_user_id' => $user->id,
                'note_title' => '',
                'note_body'  => '',
                'note_type'  => '',
                'privacy'    => '',
                'status'     => ''
            ];
            $booking->add_note($fillable);

            return ['success' => 'true', 'message' => 'All is good.'];
        }
        else{
            return ['error' => 'No bookings found to confirm. Please make the booking process again. Remember you have 60 seconds to complete the booking before it expires.'];
        }
    }
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
            //xdebug_var_dump($a); exit;

            if (strlen($vars['private_message'])>5) {
                $system_user = User::where('username','=','sysagent')->get()->first();

                $fillable_private = [
                    'by_user_id' => $system_user->id,
                    'note_title' => 'Booking status - not shown',
                    'note_body' => $vars['private_message'],
                    'note_type' => 'booking_status_changed_to_noshow',
                    'privacy' => 'employees',
                    'status' => 'unread'
                ];

                $booking->add_note($fillable_private);
            }

            if ($vars['add_invoice']==1){
                $booking_invoice = $booking->add_invoice();
                if ($booking_invoice){
                    //xdebug_var_dump($booking_invoice);
                    $booking->invoice_id = $booking_invoice->id;
                }
            }
            $booking->status = 'noshow';
            $booking->save();

            return ['success' => 'true', 'message' => 'All is good.'];
        }
        else{
            return ['error' => 'No bookings found to confirm. Please make the booking process again. Remember you have 60 seconds to complete the booking before it expires.'];
        }
    }

    public function location_calendar_day_view($date_selected = 0){
        if (!Auth::check()) {
            return redirect()->intended(route('admin/login'));
        }
        else{
            $user = Auth::user();
        }

        if ($date_selected==0){
            $date_selected = Carbon::now()->format('Y-m-d');
        }

        $default_location = 7;
        $location = ShopLocations::select('id','name')->where('id','=',$default_location)->get()->first();
        $default_activity = 3;
        $activity = ShopResourceCategory::where('id','=',$default_activity)->get()->first();

        $resources = ShopResource::where('location_id','=',$location->id)->where('category_id','=',$activity->id)->get();
        if ($resources){
            foreach($resources as $resource){
                $resources_ids[] = $resource->id;
            }
        }
        else{
            $resources_ids = [];
        }
        //xdebug_var_dump($resources);
        $hours_interval = $this->make_hours_interval($date_selected, '07:00', '23:00', 30, true, false);
        //xdebug_var_dump($hours_interval);
        $location_bookings = $this->get_location_bookings($date_selected, $location->id, $resources_ids, $hours_interval);
        //xdebug_var_dump($location_bookings['hours']['09:00']);

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

        return view('admin/bookings/calendar_location_per_day', [
            'breadcrumbs' => $breadcrumbs,
            'in_sidebar'  => $sidebar_link,
            'time_intervals' => $hours_interval,
            'location_bookings' => $location_bookings['hours'],
            'resources' => $resources_ids
        ]);
    }

    public function make_hours_interval($date_selected, $start_time='07:00', $end_time='23:00', $time_period=30, $show_all = false, $show_last = false){
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
            $currentTimeHour    = Carbon::createFromTime(6)->format('H');
            $currentTimeMinutes = Carbon::createFromTime(0,59)->format('i');
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
        }
        else{
            $begin->addHour(7);
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

        return $hours;
    }

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

    public function check_date_time_bookings($date, $time, $location, $resource, $show_more=false){
        $booking_details = [];
        $buttons_color = [
            'is_show'       => 'bg-green-jungle bg-font-green-jungle',
            'is_no_show'    => 'bg-purple-seance bg-font-purple-seance',
            'show_btn_active'   => 'btn-default border-white',
            'is_disabled'   => 'bg-default bg-font-default',
            'is_paid'       => 'bg-green-jungle bg-font-green-jungle',
            'is_not_paid'   => 'bg-purple-medium bg-font-purple-medium',
            'payment_issues'=> 'bg-red-thunderbird bg-font-red-thunderbird',
            'payment_btn_active'=>'btn-default border-white',
            'more_btn_active'   => 'btn-default border-white',
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
          ->whereIn('status',['pending','active','paid','unpaid','old','no_show'])
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

                    if ($formatted_booking['payment_type'] == 'membership'){
                        $formatted_booking['color_stripe'] = 'bg-green-haze bg-font-green-haze';
                    }
                    else{
                        $formatted_booking['color_stripe'] = 'bg-yellow-gold bg-font-yellow-gold';
                    }

                    $formatted_booking['button_show'] = 'is_disabled';
                    $formatted_booking['button_finance'] = 'is_disabled';
                    $formatted_booking['button_more'] = 'more_btn_active';
                    switch ($booking->status) {
                        case 'pending' :
                            // all disabled
                            break;
                        case 'active' :
                            $formatted_booking['button_show'] = 'show_btn_active';
                            if ($booking->payment_type=="cash"){
                                $invoice = BookingInvoice::find($booking->invoice_id);
                                if ($invoice){
                                    $formatted_booking['invoice_status'] = $invoice->status;
                                    switch ($invoice->status){
                                        case 'pending' :
                                            // the invoice is new and there was no payment tried for the amount
                                            $formatted_booking['button_finance'] = 'on';
                                            break;
                                        case 'ordered' :
                                            // we'll not use it
                                            break;
                                        case 'processing' :
                                            // a payment process started and the result is waiting
                                            $formatted_booking['button_finance'] = 'on';
                                            break;
                                        case 'completed' :
                                            // payment was done successfully
                                            $formatted_booking['button_finance'] = 'on';
                                            break;
                                        case 'cancelled' :
                                            // invoice was canceled
                                            break;
                                        case 'declined' :
                                            // payment was declined
                                            $formatted_booking['button_finance'] = 'on';
                                            break;
                                        case 'incomplete' :
                                            // payment was incomplete
                                            $formatted_booking['button_finance'] = 'on';
                                            break;
                                        case 'preordered' :
                                            // we'll not use it
                                            break;
                                    }
                                }
                                else{
                                    $formatted_booking['button_finance'] = 'on';
                                }
                            }
                            break;
                        case 'paid' :
                            // show button was clicked
                            $formatted_booking['button_show'] = 'is_show';
                            $formatted_booking['invoice_status'] = 'completed';
                            break;
                        case 'unpaid' :
                            // show button was clicked
                            $formatted_booking['button_show'] = 'is_show';
                            $formatted_booking['invoice_status'] = 'pending';
                            break;
                        case 'old' :
                            // show button was clicked
                            $formatted_booking['button_show'] = 'is_show';
                            break;
                        case 'no_show' :
                            // the player is no show
                            $formatted_booking['button_show'] = 'is_no_show';
                            break;
                    }
                }

                $booking_details[$booking->resource_id] = $formatted_booking;
            }
        }
        return $booking_details;
    }

    /* Front-End controller functions - Start */
    public function front_bookings_archive(){
        if (!Auth::check()) {
            return redirect()->intended(route('homepage'));
        }
        else{
            $user = Auth::user();
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
        $sidebar_link= 'admin-home_dashboard';

        return view('front\bookings\archive',[
            'breadcrumbs' => $breadcrumbs,
            'text_parts'  => $text_parts,
            'in_sidebar'  => $sidebar_link,
            'user'  => $user,
        ]);
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

            $canCancel  = '0';
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
            ];

            return $bookingDetails;
        }
        else{
            return $bookingDetails;
        }
    }
    /* Front-End controller functions - Stop */
}
