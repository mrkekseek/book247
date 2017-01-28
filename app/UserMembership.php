<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Validator;
use Regulus\ActivityLog\Models\Activity;

class UserMembership extends Model
{
    protected $table = 'user_memberships';

    public static $attributeNames = array(
        'user_id'       => 'User ID',
        'membership_id' => 'Membership ID',
        'day_start'     => 'Start Day',
        'day_stop'      => 'End Day',
        'membership_name'   => 'Membership Name',
        'invoice_period'    => 'Invoice Period',
        'binding_period'    => 'Binding Period',
        'sign_out_period'   => 'Sign Out Period',
        'price'         => 'Price',
        'discount'      => 'Discount',
        'membership_restrictions' => 'Membership Restrictions',
        'signed_by'     => 'Signed By',
        'contract_number'   => 'Contract Number'
    );

    public static $message = array();

    protected $fillable = [
        'user_id',
        'membership_id',
        'day_start',
        'day_stop',
        'membership_name',
        'invoice_period',
        'binding_period',
        'sign_out_period',
        'price',
        'discount',
        'membership_restrictions',
        'signed_by',
        'contract_number'
    ];

    public static function rules($method, $id=0){
        switch($method){
            case 'GET':
            case 'DELETE':
            {
                return [];
            }
            case 'POST':
            {
                return [
                    'user_id'   => 'required|exists:users,id',
                    'membership_id' => 'required|exists:membership_plans,id',
                    'day_start' => 'required|date',
                    'day_stop'  => 'required|date',
                    'membership_name'   => 'required|min:3',
                    'invoice_period'    => 'required|numeric',
                    'binding_period'    => 'required|numeric',
                    'sign_out_period'   => 'required|numeric',
                    'price'     => 'required|numeric',
                    'discount'  => 'numeric',
                    'membership_restrictions'   => 'required|min:3',
                    'signed_by' => 'required|exists:users,id',
                    'status'    => 'required|in:active,suspended,canceled,expired',
                    'contract_number'   => 'required|numeric|unique:user_memberships,contract_number|min:3'
                ];
            }
            case 'PUT':
            case 'PATCH':
            {
                return [
                    'user_id'   => 'required|exists:users,id',
                    'membership_id' => 'required|exists:membership_plans,id',
                    'day_start' => 'required|date',
                    'day_stop'  => 'required|date',
                    'membership_name'   => 'required|min:3',
                    'invoice_period'    => 'required|numeric',
                    'binding_period'    => 'required|numeric',
                    'sign_out_period'   => 'required|numeric',
                    'price'     => 'required|numeric',
                    'discount'  => 'numeric',
                    'membership_restrictions'   => 'required|min:3',
                    'signed_by' => 'required|exists:users,id',
                    'status'    => 'required|in:active,suspended,canceled,expired',
                    'contract_number'   => 'required|numeric|min:3|unique:user_memberships,contract_number'.($id ? ",$id,id" : '')
                ];
            }
            default:break;
        }
    }

    public function plan_blueprint(){
        return $this->belongsTo('App\MembershipPlan', 'membership_id', 'id');
    }

    public function get_plan_restrictions(){
        $my_restrictions = json_decode($this->membership_restrictions);
        foreach($my_restrictions as $rest){
            $restrictions[] = [
                'id'            => $rest->id,
                'title'         => $rest->title,
                'name'          => $rest->name,
                'description'   => $rest->description,
                'color'         => $rest->color,
                'value'         => $rest->value,
                'min_value'     => $rest->min_value,
                'max_value'     => $rest->max_value,
                'time_start'    => $rest->time_start,
                'time_end'      => $rest->time_end,
                'special_permissions'   => isset($rest->special_permissions)?$rest->special_permissions:json_encode([])
            ];
        }

        return $restrictions;
    }

    public function get_plan_details(){
        $invoice_period = [
            '7'   => 'once every 7 days',
            '14'  => 'once every 14 days',
            '30'  => 'one per month',
            '90'  => 'once every three months',
            '180' => 'once every six months',
            '360' => 'once per year'
        ];

        if ($this->user_id==$this->signed_by){
            $signed_by_link = '';
            $signed_by_name = 'Self';
        }
        else{
            $user = User::where('id','=',$this->signed_by)->get()->first();
            if ($user){
                $signed_by_name = $user->first_name.' '.$user->middle_name.' '.$user->last_name;
                if ($user->hasRole(['front_member','front_user'])){
                    // a member
                    $signed_by_link = route('admin/front_users/view_user/',$user->id);
                }
                else{
                    // a user
                    $signed_by_link = route('admin/back_users/view_user/',$user->id);
                }
            }
            else{
                $signed_by_link = '';
                $signed_by_name = '??ERROR??';
            }
        }

        $plan_details = [
            'price'         => $this->price,
            'day_start'     => Carbon::createFromFormat('Y-m-d',$this->day_start)->format('j F, Y'),
            'day_stop'      => Carbon::createFromFormat('Y-m-d',$this->day_stop)->format('j F, Y'),
            'membership_name'   => $this->membership_name,
            'invoice_period'    => $invoice_period[$this->invoice_period],
            'binding_period'    => $this->binding_period,
            'sign_out_period'   => $this->sign_out_period,
            'discount'      => $this->discount,
            'signed_by_name'    => $signed_by_name,
            'signed_by_link'    => $signed_by_link
        ];

        return $plan_details;
    }

    public function create_new(User $user, MembershipPlan $plan, User $signed_by, $day_start = false, $contract_number = 0) {
        $to_be_paid = $plan->get_price();

        if ($day_start==false){
            $day_start = Carbon::today();
            $day_stop  = Carbon::today()->addMonthsNoOverflow($plan->binding_period);
        }
        else{
            $day_start = Carbon::createFromFormat('Y-m-d H:i:s', $day_start.' 00:00:00');
            $day_stop  = Carbon::instance($day_start)->addMonthsNoOverflow($plan->binding_period);
        }

        /*if ($day_start==false){
            $day_start = Carbon::today();
            $day_stop  = Carbon::today()->addMonthsNoOverflow($plan->binding_period);
        }
        else{
            $day_start = Carbon::createFromFormat('Y-m-d H:i:s', $day_start.' 00:00:00');
            $day_stop  = Carbon::instance($day_start)->addMonthsNoOverflow($plan->binding_period);

            $monthsToAdd = Carbon::today()->diffInMonths($day_stop);
            if ($monthsToAdd>=0){
                $day_stop->addMonthsNoOverflow($monthsToAdd+($plan->sign_out_period + 1));
                //echo 'Day stop update ['.($monthsToAdd+($plan->sign_out_period - 1)).'] : '.$day_stop->format('Y-m-d').'<br />';
            }
        }*/

        $fillable = [
            'user_id'       => $user->id,
            'membership_id' => $plan->id,
            'day_start'     => $day_start->toDateString(),
            'day_stop'      => $day_stop->toDateString(),
            'membership_name'   => $plan->name,
            'invoice_period'    => $plan->plan_period,
            'binding_period'    => $plan->binding_period,
            'sign_out_period'   => $plan->sign_out_period,
            'price'         => $to_be_paid->price,
            'discount'      => 0,
            'membership_restrictions'   => '',
            'signed_by'     => $signed_by->id,
            'status'        => 'active',
            'contract_number'   => $this->get_next_membership_number($contract_number)
        ];

        $membership_restriction = $plan->get_restrictions(true);
        $fillable['membership_restrictions'] = json_encode($membership_restriction);

        $validator = Validator::make($fillable, UserMembership::rules('POST'), UserMembership::$message, UserMembership::$attributeNames);
        if ($validator->fails()){
            //xdebug_var_dump($validator->getMessageBag()->toArray()); exit;
            return false;
        }

        try {
            $the_membership = UserMembership::create($fillable);
            Activity::log([
                'contentId'     => $user->id,
                'contentType'   => 'user_membership',
                'action'        => 'New Membership Assignment',
                'description'   => 'New membership plan assigned to customer : '.$the_membership->id,
                'details'       => 'Created by user : '.$signed_by->id,
                'updated'       => false,
            ]);

            // assign invoice for the newly created membership
            $member_invoice = new Invoice();
            $member_invoice->user_id = $user->id;
            $member_invoice->employee_id = $signed_by->id;
            $member_invoice->invoice_type = 'membership_plan_assignment_invoice';
            $member_invoice->invoice_reference_id = $the_membership->id;
            $member_invoice->invoice_number = Invoice::next_invoice_number();
            $member_invoice->status = 'pending';

            $member_invoice->save();

            $invoice_item = [
                'item_name'     => $the_membership->membership_name,
                'item_type'     => 'user_memberships',
                'item_reference_id'    => $the_membership->id,

                'quantity'      => 1,
                'price'         => $the_membership->price,
                'vat'           => 0,
                'discount'      => $the_membership->discount
            ];
            $member_invoice->add_invoice_item($invoice_item);

            if ($plan->administration_fee_amount!=0){
                // we have the one time administration fee here
                $invoice_item = [
                    'item_name'     => $plan->administration_fee_name,
                    'item_type'     => 'user_memberships',
                    'item_reference_id'    => $the_membership->id,

                    'quantity'      => 1,
                    'price'         => $plan->administration_fee_amount,
                    'vat'           => 0,
                    'discount'      => $the_membership->discount
                ];
                $member_invoice->add_invoice_item($invoice_item);
            }

            $the_membership->generate_invoices_plan();

            return $the_membership;
        }
        catch (Exception $e) {
            return false;
        }
    }

    public function generate_invoices_plan_old(){
        // get first generated invoice
        $invoiceNo = 1;
        $firstPrice = 0;
        $invoice = Invoice::with('items')->where('invoice_type','=','membership_plan_assignment_invoice')->where('invoice_reference_id','=',$this->id)->get()->first();
        $invoice_date = Carbon::createFromFormat('Y-m-d',$this->day_start);
        foreach($invoice->items as $item){
            $firstPrice+=$item->total_price;
        }

        $invoice_last_active = Carbon::createFromFormat('Y-m-d', $this->day_start);
        if ($this->invoice_period==7 || $this->invoice_period==14){
            $invoice_last_active->addDays($this->invoice_period)->addDays(-1);
        }
        elseif($this->invoice_period==30 || $this->invoice_period==90 || $this->invoice_period==180){
            $invoice_last_active->addMonths($this->invoice_period/30)->addDays(-1);
        }
        else{
            $invoice_last_active->addYear()->addDays(-1);
        }

        $fillable = [
            'user_membership_id'=> $this->id,
            'item_name'         => 'Invoice #' . $invoiceNo . ' for '.$this->membership_name,
            'price'             => $firstPrice,
            'discount'          => $this->discount,
            'issued_date'       => $invoice_date->format('Y-m-d'),
            'last_active_date'  => $invoice_last_active->format('Y-m-d'),
            'status'            => 'pending'
        ];
        UserMembershipInvoicePlanning::create($fillable);

        $invoiceNo++;
        unset($invoice_last_active);
        unset($fillable);

        $invoice_last_active = Carbon::createFromFormat('Y-m-d',$this->day_start);
        $end_date = Carbon::createFromFormat('Y-m-d', $this->day_stop);

        while ($end_date->gt($invoice_last_active->addDay())){
            if ($this->invoice_period==7 || $this->invoice_period==14){
                $invoice_date->addDays($this->invoice_period);
                $invoice_last_active = Carbon::instance($invoice_date)->addDays($this->invoice_period)->addDays(-1);
            }
            elseif($this->invoice_period==30 || $this->invoice_period==90 || $this->invoice_period==180){
                $invoice_date->addMonths($this->invoice_period/30);
                $invoice_last_active = Carbon::instance($invoice_date)->addMonths($this->invoice_period/30)->addDays(-1);
            }
            else{
                $invoice_date->addYear();
                $invoice_last_active = Carbon::instance($invoice_date)->addYear()->addDays(-1);
            }

            $fillable = [
                'user_membership_id'=> $this->id,
                'item_name'         => 'Invoice #' . $invoiceNo . ' for '.$this->membership_name,
                'price'             => $this->price,
                'discount'          => $this->discount,
                'issued_date'       => $invoice_date->format('Y-m-d'),
                'last_active_date'  => $invoice_last_active->format('Y-m-d'),
                'status'            => 'pending'
            ];

            $validator = Validator::make($fillable, UserMembershipInvoicePlanning::rules('POST'), UserMembershipInvoicePlanning::$message, UserMembershipInvoicePlanning::$attributeNames);
            if ($validator->fails()){
                //echo json_encode($validator->getMessageBag()->toArray());
                return false;
            }

            UserMembershipInvoicePlanning::create($fillable); //exit;
            $invoiceNo++;
        }

        $firstInvoice = Invoice::where('invoice_type','=','membership_plan_assignment_invoice')->where('invoice_reference_id','=',$this->id)->get()->first();
        $firstInv = UserMembershipInvoicePlanning::where('user_membership_id','=',$this->id)->orderBy('issued_date','ASC')->get()->first();
        $firstInv->status = 'old';
        $firstInv->invoice_id = $firstInvoice->id;
        $firstInv->save();

        $lastInv = UserMembershipInvoicePlanning::where('user_membership_id','=',$this->id)->orderBy('issued_date','DESC')->get()->first();
        $lastInv->status = 'last';
        $lastInv->save();
    }

    public function generate_invoices_plan(){
        // get first generated invoice
        $invoiceNo = 1;
        $firstPrice = 0;
        $invoice = Invoice::with('items')->where('invoice_type','=','membership_plan_assignment_invoice')->where('invoice_reference_id','=',$this->id)->get()->first();
        $invoice_date = Carbon::createFromFormat('Y-m-d',$this->day_start);
        foreach($invoice->items as $item){
            $firstPrice+=$item->total_price;
        }

        $invoice_date_intervals = UserMembershipInvoicePlanning::generate_invoice_list_of_dates($this->day_start, $this->invoice_period);
        if (!$invoice_date_intervals){
            return false;
        }

        $invoice_last_active = $invoice_date_intervals[0]['last_day'];
        $fillable = [
            'user_membership_id'=> $this->id,
            'item_name'         => 'Invoice #' . $invoiceNo . ' for '.$this->membership_name,
            'price'             => $firstPrice,
            'discount'          => $this->discount,
            'issued_date'       => $invoice_date->format('Y-m-d'),
            'last_active_date'  => $invoice_last_active->format('Y-m-d'),
            'status'            => 'pending'
        ];
        // we create first invoice
        UserMembershipInvoicePlanning::create($fillable);
        $invoiceNo++;

        $today_date = Carbon::today();
        foreach ($invoice_date_intervals as $key=>$val){
            if ($key==0){
                continue;
            }
            elseif( Carbon::instance($today_date)->addMonthsNoOverflow($this->signout_period)->lt(Carbon::instance($val['first_day']))
                && Carbon::instance($invoice_date_intervals[0]['first_day'])->addMonthsNoOverflow($this->binding_period + $this->signout_period)->lt($val['first_day'])){
                break;
            }

            $fillable = [
                'user_membership_id'=> $this->id,
                'item_name'         => 'Invoice #' . $invoiceNo . ' for '.$this->membership_name,
                'price'             => $this->price,
                'discount'          => $this->discount,
                'issued_date'       => $val['first_day']->format('Y-m-d'),
                'last_active_date'  => $val['last_day']->format('Y-m-d'),
                'status'            => 'pending'
            ];

            $validator = Validator::make($fillable, UserMembershipInvoicePlanning::rules('POST'), UserMembershipInvoicePlanning::$message, UserMembershipInvoicePlanning::$attributeNames);
            if ($validator->fails()){
                //echo json_encode($validator->getMessageBag()->toArray());
                return false;
            }

            UserMembershipInvoicePlanning::create($fillable); //exit;
            $invoiceNo++;
        }

        $firstInvoice = Invoice::where('invoice_type','=','membership_plan_assignment_invoice')->where('invoice_reference_id','=',$this->id)->get()->first();
        $firstInv = UserMembershipInvoicePlanning::where('user_membership_id','=',$this->id)->orderBy('issued_date','ASC')->get()->first();
        $firstInv->status = 'old';
        $firstInv->invoice_id = $firstInvoice->id;
        $firstInv->save();

        $lastInv = UserMembershipInvoicePlanning::where('user_membership_id','=',$this->id)->orderBy('issued_date','DESC')->get()->first();
        $lastInv->status = 'last';
        $lastInv->save();
    }

    public static function invoice_membership_period($invoiceDate, $invoicePeriod){
        if ($invoicePeriod==7 || $invoicePeriod==14){
            //$invoiceDate->addDays($invoicePeriod);
            $invoice_last_active = Carbon::instance($invoiceDate)->addDays($invoicePeriod)->addDays(-1);
        }
        elseif($invoicePeriod==30 || $invoicePeriod==90 || $invoicePeriod==180){
            //$invoiceDate->addMonths($invoicePeriod/30);
            $invoice_last_active = Carbon::instance($invoiceDate)->addMonths($invoicePeriod/30)->addDays(-1);
        }
        else{
            //$invoiceDate->addYear();
            $invoice_last_active = Carbon::instance($invoiceDate)->addYear()->addDays(-1);
        }

        return [
            'first_day' => $invoiceDate,
            'last_day'  => $invoice_last_active
        ];
    }

    public function get_plan_requests($all = false){
        $plan_requests = [];
        if ($all==false){
            $status = ['active','old'];
        }
        else{
            $status = ['active','old','cancelled'];
        }

        $requests = UserMembershipAction::where('user_membership_id','=',$this->id)->whereIn('status',$status)->orderBy('start_date','ASC')->get();
        foreach($requests as $request){
            $user = User::where('id','=',$request->added_by)->get()->first();
            $user_name = $user->first_name.' '.$user->middle_name.' '.$user->last_name;
            if ($user->is_back_user()){
                $user_link = route('admin/back_users/view_user/personal_info',['id' => $user->id]);
            }
            else{
                $user_link = route('admin/front_users/view_user/personal_info',['id' => $user->id]);
            }

            $plan_requests[] = [
                'id'            => $request->id,
                'action_type'   => $request->action_type,
                'start_date'    => Carbon::createFromFormat('Y-m-d H:i:s',$request->start_date.' 00:00:00'),
                'end_date'      => Carbon::createFromFormat('Y-m-d H:i:s',$request->end_date.' 00:00:00'),
                'added_by_name' => $user_name,
                'added_by_link' => $user_link,
                'additional_values' => json_decode($request->additional_values),
                'notes'         => $request->notes,
                'status'        => $request->status,
                'processed'     => $request->processed,
                'updated_at'    => Carbon::createFromFormat('Y-m-d H:i:s',$request->updated_at)->format('d M Y H:i'),
                'created_at'    => Carbon::createFromFormat('Y-m-d H:i:s',$request->created_at)->format('d M Y H:i')
            ];
        }

        return $plan_requests;
    }

    public function get_next_membership_number($requested_number = 0){
        if (is_numeric($requested_number)){
            $requested_number = intval($requested_number);
        }
        else{
            $requested_number = 0;
        }

        if ($requested_number!=0){
            $get_number = UserMembership::where('contract_number','=',$requested_number)->get()->first();
            if (!$get_number){
                return $requested_number;
            }
        }

        $current_last_membership_number = UserMembership::whereNotNull('contract_number')->orderBy('contract_number', 'DESC')->first();
        if ($current_last_membership_number){
            $next_number = $current_last_membership_number->contract_number + 1;
        }
        else{
            $next_number = 10001;
        }

        return $next_number;
    }
}