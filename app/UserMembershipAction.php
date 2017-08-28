<?php

namespace App;

use App\Http\Controllers\MembershipController;
use \App\Role;
use App\User;
use App\UserMembershipInvoicePlanning;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class UserMembershipAction extends Model
{
    protected $table = 'user_membership_actions';

    public static $attributeNames = array(
        'user_membership_id'=> 'Membership ID',
        'action_type'       => 'Action Type',
        'additional_values' => 'Additional Values',
        'start_date'        => 'Start Date',
        'end_date'          => 'End Date',
        'added_by'  => 'Added By',
        'notes'     => 'Notes',
        'processed' => 'Processed',
        'status'    => 'Status'
    );

    public static $message = array();

    protected $fillable = [
        'user_membership_id',
        'action_type',
        'additional_values',
        'start_date',
        'end_date',
        'added_by',
        'notes',
        'processed',
        'status'
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
                    'user_membership_id' => 'required|exists:user_memberships,id',
                    'action_type'   => 'required|in:freeze,cancel,update,unknown',
                    'additional_values'  => 'min:2|max:6000',
                    'start_date'    => 'required|date',
                    'end_date'  => 'required|date',
                    'added_by'  => 'required|exists:users,id',
                    'processed' => 'required|in:0,1',
                    'status'    => 'required|in:active,old,cancelled'
                ];
            }
            case 'PUT':
            case 'PATCH':
            {
                return [
                    'user_membership_id' => 'required|exists:user_memberships,id',
                    'action_type'   => 'required|in:freeze,cancel,update,unknown',
                    'additional_values'  => 'min:2|max:6000',
                    'start_date'    => 'required|date',
                    'end_date'  => 'required|date',
                    'added_by'  => 'required|exists:users,id',
                    'processed' => 'required|in:0,1',
                    'status'    => 'required|in:active,old,cancelled'
                ];
            }
            default:break;
        }
    }

    public function add_note($noteText){
        $notes = json_decode($this->notes);
        $notes[] = $noteText;
        $this->notes = json_encode($notes);
        $this->save();
    }

    public function process_action($userID = 1){
        $from_date  = Carbon::createFromFormat('Y-m-d H:i:s',$this->start_date.' 00:00:00');
        $to_date    = Carbon::createFromFormat('Y-m-d H:i:s',$this->end_date.' 00:00:00')->addDay(); // we need next day

        // get user active/suspended membership
        $userMembership = UserMembership::where('id','=',$this->user_membership_id)->whereIn('status',['active','suspended','pending'])->first();
        switch ($this->action_type) {
            case 'freeze' : {
                if ($this->processed == 0) {
                    // we need to recalculate invoices so we search for next pending invoice in the membership
                    $nextInvoice = UserMembershipInvoicePlanning::where('user_membership_id', '=', $userMembership->id)->where('status', '=', 'pending')->orderBy('issued_date', 'ASC')->first();
                    if ($nextInvoice) {
                        // if the freeze starts between next invoices start/end date, then we rebuild the invoices
                        $invoiceStart = Carbon::createFromFormat('Y-m-d H:i:s', $nextInvoice->issued_date . ' 00:00:00');
                        $invoiceEnd = Carbon::createFromFormat('Y-m-d H:i:s', $nextInvoice->last_active_date . ' 00:00:00');

                        if ($from_date->between($invoiceStart, $invoiceEnd)) {
                            // check the planned invoices that needs to be pushed out of the freeze period
                            MembershipController::freeze_membership_rebuild_invoices($userMembership);

                            $this->add_note('Membership freeze schedule - invoices recalculation today : ' . time() . ' : by System User');
                            $this->processed = 1;
                            $this->save();
                        }
                    }

                    //echo '- freeze processed today for Membership ID '.$userMembership->id.PHP_EOL;
                }
                else {
                    // we need to freeze the membership plan if the freeze starts today
                    if (Carbon::today()->eq($from_date)) {
                        $userMembership->status = 'suspended';
                        $userMembership->save();

                        $this->add_note('Membership frozen today : ' . time() . ' : by System User');
                        $this->processed = 1;
                        $this->save();

                        //echo '- Freeze started today for membership ID '.$userMembership->id.PHP_EOL;
                    } // we need to unfreez the membership plan if the freeze stops today
                    elseif (Carbon::today()->eq($to_date)) {
                        $userMembership->status = 'active';
                        $userMembership->save();

                        $this->status = 'old';
                        $this->add_note('Membership unfrozen today : ' . time() . ' : by System User');
                        $this->save();

                        //echo '- Freeze over today for membership ID '.$userMembership->id.PHP_EOL;
                    }
                    else {
                        //echo '- Freeze not today, starts on '.$from_date->format('Y-m-d').' for membership ID '.$userMembership->id.PHP_EOL;
                        continue;
                    }
                }
                break;
            }
            case 'cancel' : {
                // we check if end_date + 1Day, for the planned action, is equal to today date, so we cancel the membership plan
                if ($from_date->isToday()) {
                    // check for pending invoices in the future and delete them
                    if ($userMembership->created_at->isToday()){
                        // we get all pending invoices
                        $futureInvoices = UserMembershipInvoicePlanning::where('user_membership_id','=',$userMembership->id)->get();
                    }
                    else{
                        // we get all future pending invoices
                        $futureInvoices = UserMembershipInvoicePlanning::where('user_membership_id','=',$userMembership->id)->where('issued_date','>=',$to_date->format('Y-m-d'))->get();
                    }

                    if ($futureInvoices){
                        foreach($futureInvoices as $invoice){
                            if ($invoice->status!='old'){
                                // invoice is not issued yet so we can delete this planned invoice
                                $invoice->delete();
                            }
                            else{
                                // invoice is issues so we need to cancel the issued invoice
                                $issuedInvoice = Invoice::where('id','=',$invoice->invoice_id)->first();
                                if ($issuedInvoice){
                                    $issuedInvoice->cancel_invoice(true);
                                }
                            }
                        }
                    }

                    $userMembership->status = 'canceled';
                    $userMembership->save();

                    $this->processed = 1;
                    $this->status = 'old';
                    $this->add_note('Membership canceled today : ' . time() . ' : by System User');
                    $this->save();

                    $memberRole = Role::where('name','=','front-member')->get()->first();
                    $userRole   = Role::where('name','=','front-user')->get()->first();

                    $userID = User::find($userMembership->user_id);
                    $userID->detachRole($memberRole);

                    if ($userID->hasRole('front-user')===false){
                        $userID->attachRole($userRole);
                    }

                    $searchMembers = new OptimizeSearchMembers();
                    $searchMembers->add_missing_members([$userID->id]);

                    //echo '- Cancelled today - membership ID '.$userMembership->id.PHP_EOL;
                }
                else{
                    //echo '- Not today - cancel on '.$from_date->format('Y-m-d').PHP_EOL;
                }
                break;
            }
            case 'update' : {
                if (Carbon::today()->eq($from_date)) {
                    $additional_values = json_decode($this->additional_values);

                    // check the planned invoices that needs to be pushed out of the freeze period
                    MembershipController::update_membership_rebuild_invoices($userMembership);

                    // change active membership details : name, price, discount, restrictions
                    $userMembership->membership_id = $additional_values->new_membership_plan_id;
                    $userMembership->membership_name = $additional_values->new_membership_plan_name;
                    $userMembership->price = $additional_values->new_membership_plan_price;
                    $userMembership->discount = $additional_values->new_membership_plan_discount;
                    $userMembership->membership_restrictions = $additional_values->new_membership_restrictions;
                    $userMembership->save();

                    // mark action as old
                    $this->processed = 1;
                    $this->status = 'old';
                    $this->save();

                    $searchMembers = new OptimizeSearchMembers();
                    $searchMembers->add_missing_members([$userMembership->user_id]);

                    //echo '- updated today - membership ID '.$userMembership->id.PHP_EOL;
                }
                else{
                    //echo '- update will run on '.$from_date->format('Y-m-d').' for membership ID : '.$userMembership->id.PHP_EOL;
                }
                break;
            }
            default : {
                $this->processed = 1;
                $this->status = 'old';
                $this->add_note('Unknown action type found; error returned.');
                $this->save();
                break;
            }
        }
    }
}
