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
use Auth;
use Carbon\Carbon;

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

    public function cancel_booking(Request $request){
        if (!Auth::check()) {
            //return redirect()->intended(route('admin/login'));
            return ['error' => 'Authentication Error'];
        }
        else{
            $user = Auth::user();
        }

        $vars = $request->only('search_key');
        $booking = Booking::where('for_user_id','=',$user->id)->orWhere('by_user_id','=',$user->id)->where('search_key','=',$vars['search_key'])->get()->first();
        if ($booking){
            Booking::whereIn('status',['active', 'pending'])->where('search_key','=',$vars['search_key'])->update(['status'=>'canceled']);
            return ['success' => 'true', 'message' => 'All is good.'];
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

        $allBookings = Booking::where('by_user_id','=',$userID)->orWhere('for_user_id','=',$userID)->whereNotIn('status',['expired'])->get();
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
                        $status = '<a href="#'.$booking->search_key.'" class="btn green-jungle">Make Payment</a>';
                    }
                }
                else{
                    if ($this->can_cancel_booking($booking->id)) {
                        $status = '<span data-id="' . $booking->search_key . '" class="cancel_booking btn red-flamingo">Cancel</span> ';
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

    /* Front-End controller functions - Start */
    public function front_bookings_archive(){
        if (!Auth::check()) {
            return redirect()->intended(route('login'));
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
        }
        else{
            return $bookingDetails;
        }

        $vars = $request->only('search_key');
        $booking = Booking::where('search_key','=',$vars['search_key'])->get()->first();
        if ($booking){
            if ($user->id!=$booking->for_user_id && $user->id!=$booking->by_user_id){
                return $bookingDetails;
            }

            $location = ShopLocations::find($booking->location_id);
            $locationName = $location->name;
            $room = ShopResource::find($booking->resource_id);
            $roomName = $room->name;
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

            $bookingDetails = [
                'bookingDate'   => Carbon::createFromFormat('Y-m-d', $booking->date_of_booking)->format('l, M jS, Y'),
                'timeStart'     => Carbon::createFromFormat('H:i:s', $booking->booking_time_start)->format('H:i'),
                'timeStop'      => Carbon::createFromFormat('H:i:s', $booking->booking_time_stop)->format('H:i'),
                'paymentType'   => $booking->payment_type,
                'paymentAmount' => $booking->payment_amount,
                'financialDetails' => $financeDetails,
                'location'      => $locationName,
                'room'          => $roomName,
                'category'      => $categoryName,
                'byUserName'    => @$madeBy,
                'forUserName'   => @$madeFor,
                'forUserID'     => @$booking->for_user_id
            ];

            return $bookingDetails;
        }
        else{
            return $bookingDetails;
        }
    }
    /* Front-End controller functions - Stop */
}
