<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Http\Requests;



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

        $cash_terminals = CashTerminal::with('shopLocation')->get();
        $shops = ShopLocations::all();

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
            'cash_terminals' => $cash_terminals,
            'shops' => $shops,
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
            'title'     => 'All Cash Terminals',
            'subtitle'  => 'add/edit/view terminals',
            'table_head_text1' => 'Backend Roles Permissions List'
        ];
        $sidebar_link= 'admin-backend-memberships-all_plans';

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
            return ['success' => false, 'errors' => 'Error while trying to authenticate. Login first then use this function.', 'title' => 'Not logged in'];
        }
        else{
            $user = Auth::user();
        }

        $vars = $request->only('name', 'price', 'plan_period', 'administration_fee_name', 'administration_fee_amount', 'plan_calendar_color', 'membership_short_description', 'membership_long_description');

        if (in_array($vars['plan_period'], ['7d','14d','1m','3m','6m','12m'])){
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
            'title'     => 'Edit Plane - Full month free',
            'subtitle'  => '',
            'table_head_text1' => 'Backend Roles Permissions List'
        ];
        $sidebar_link= 'admin-backend-memberships-all_plans';

        return view('admin/membership_plans/edit_plan', [
            'breadcrumbs' => $breadcrumbs,
            'text_parts'  => $text_parts,
            'in_sidebar'  => $sidebar_link,
            'cash_terminals' => $cash_terminals,
            'shops' => $shops,
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
}
