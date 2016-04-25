<?php

namespace App\Http\Controllers;

use App\Booking;
use App\ShopResource;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;
use Validator;
use Auth;

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
        $vars = $request->only('selected_activity', 'selected_date', 'selected_location', 'selected_payment', 'selected_resource', 'selected_time');

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

        $fillable = [
            'by_user_id'    => $user->id,
            'for_user_id'   => $user->id,
            'location_id'   => $vars['selected_location'],
            'resource_id'   => $vars['selected_resource'],
            'status'        => 'pending',
            'date_of_booking'   => $vars['selected_date'],
            'booking_time_start'    => trim($vars['selected_time']),
            'booking_time_stop'     => trim($vars['selected_time']),
            'payment_type'  => $vars['selected_payment'],
            'membership_id' => 1,
            'invoice_id'    => 1
        ];
        $validator = Validator::make($fillable, Booking::rules('POST'), Booking::$message, Booking::$attributeNames);
        if ($validator->fails()){
            return array(
                'success' => false,
                'errors' => $validator->getMessageBag()->toArray()
            );
        }

        try {
            Booking::create($fillable);
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
}
