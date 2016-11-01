<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;

use App\InvoiceItems;
use App\ShopLocations;
use App\ShopResource;
use App\ShopResourceCategory;
use Carbon\Carbon;

class Booking extends Model
{
    protected $table = 'bookings';

    public static $attributeNames = array(
        'by_user_id'    => 'By Member',
        'for_user_id'   => 'For Member',
        'employee_involved_id'  => 'By Employee',
        'location_id'   => 'Location ID',
        'resource_id'   => 'Resource ID',
        'status'        => 'Booking Status',
        'date_of_booking'   => 'Date of Booking',
        'booking_time_start'=> 'Booking Start',
        'booking_time_stop' => 'Booking End',
        'custom_color_code' => 'Custom Color for Booking',
        'payment_type'  => 'Payment Type',
        'membership_id' => 'Membership ID',
        'membership_product_id' => 'Membership Product ID',
        'invoice_id'    => 'Invoice ID',
        'search_key'    => 'Search Key'
    );

    public static $message = array();

    protected $fillable = [
        'by_user_id',
        'for_user_id',
        'employee_involved_id',
        'location_id',
        'resource_id',
        'status',
        'date_of_booking',
        'booking_time_start',
        'booking_time_stop',
        'custom_color',
        'payment_type',
        'membership_id',
        'membership_product_id',
        'invoice_id',
        'search_key',
        'payment_amount',
    ];

    public static function new_search_key(){
        $search_key = substr( base64_encode(openssl_random_pseudo_bytes(32)),0 ,63 );
        $exists = Booking::where('search_key','=',$search_key)->get()->first();
        while ($exists){
            $search_key = substr( base64_encode(openssl_random_pseudo_bytes(32)),0 ,63 );
            $exists = Booking::where('search_key','=',$search_key)->get()->first();
        }

        return $search_key;
    }

    /**
     * @param $user - the logged in user that is requesting the summary
     * @return array $bookingDetails = [
                        'bookingDate'   => date format('l, M jS, Y'),
                        'timeStart'     => time start, format('H:i'),
                        'timeStop'      => time stop, format('H:i'),
                        'paymentType'   => [card, membership, recurrent],
                        'paymentAmount' => amount integer,
                        'financialDetails' => details,
                        'location'      => location Name,
                        'room'          => room Name,
                        'roomPrice'     => room Price,
                        'category'      => category Name,
                        'byUserName'    => made By user,
                        'forUserName'   => made For user,
                        'forUserID'     => User id for the player,
                        'canCancel'     => can Cancel,
                        'canModify'     => can Modify,
                        'invoiceLink'   => invoice Link,
                        'canNoShow'     => no Show status button,
                        'bookingNotes'  => all booking Notes]
     */
    public function get_summary_details($is_backend_employee=false){
        $canCancel   = '0';
        $canModify   = '0';
        $invoiceLink = '0';
        $noShow      = '0';
        $recurrentList = '0';

        if ($is_backend_employee){
            switch ($this->status) {
                case'active' :
                    $canCancel  = '1';
                    $canModify  = '0';
                    // if time of booking is less than current time + a time limit of x days
                    $noShow = '1';
                    break;
                case 'paid' :
                    $invoiceLink = '1';
                    // if time of booking is less than current time + a time limit of x days
                    $noShow = '1';
                    break;
                case 'unpaid' :
                    $canModify  = '0';
                    $invoiceLink = '1';
                    // if time of booking is less than current time + a time limit of x days
                    $noShow = '1';
                    break;
                case 'noshow' :
                    $invoiceLink = '1';
                    break;
                case 'old' :
                    $noShow = '1';
                    break;
                case 'pending' :
                case 'expired' :
                case 'canceled' :
                default:
                    break;
            }
        }

        $location = ShopLocations::find($this->location_id);
        $locationName = $location->name;

        $room = ShopResource::find($this->resource_id);
        $roomName = $room->name;
        $roomPrice= $room->get_price($this->date_of_booking, $this->booking_time_start);
        //$roomPrice= $room->session_price;

        $category = ShopResourceCategory::find($room->category_id);
        $categoryName = $category->name;

        $userBy = User::find($this->by_user_id);
        if ($userBy) {
            $madeBy = $userBy->first_name . ' ' . $userBy->middle_name . ' ' . $userBy->last_name;
        }
        $userFor = User::find($this->for_user_id);
        if ($userFor) {
            $madeFor = $userFor->first_name . ' ' . $userFor->middle_name . ' ' . $userFor->last_name;
        }
        $employeeID = User::find($this->employee_involved_id);
        if ($employeeID) {
            $employeeInvolved = $employeeID->first_name . ' ' . $employeeID->middle_name . ' ' . $employeeID->last_name.' [employee]';
        }

        if ($this->payment_type == 'cash'){
            $financeDetails = ' Payment of '.$this->payment_amount;
        }
        elseif($this->payment_type == 'recurring'){
            $financeDetails = "Recurrent Booking of ".$this->payment_amount;
            $recurrentList = '1';
        }
        else{
            $financeDetails = "Membership included";
        }

        $allNotes = [];
        if (sizeof($this->notes)>0){
            foreach($this->notes as $note){
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
            'addedOn'       => Carbon::createFromFormat('Y-m-d H:i:s', $this->created_at)->format('l, M jS, Y \a\t H:i'),
            'bookingDate'   => Carbon::createFromFormat('Y-m-d', $this->date_of_booking)->format('l, M jS, Y'),
            'timeStart'     => Carbon::createFromFormat('H:i:s', $this->booking_time_start)->format('H:i'),
            'timeStop'      => Carbon::createFromFormat('H:i:s', $this->booking_time_stop)->format('H:i'),
            'paymentType'   => $this->payment_type,
            'paymentAmount' => $this->payment_amount,
            'financialDetails' => $financeDetails,
            'location'      => $locationName,
            'room'          => $roomName,
            'roomPrice'     => $roomPrice,
            'category'      => $categoryName,
            'byUserName'        => isset($madeBy)?$madeBy:'',
            'forUserName'       => isset($madeFor)?$madeFor:'',
            'employee_involved' => isset($employeeInvolved)?$employeeInvolved:'',
            'forUserID'     => @$this->for_user_id,
            'canCancel'     => $canCancel,
            'canCancelRules'  => $this->can_cancel()==true?1:0,
            'canModify'     => $canModify,
            'invoiceLink'   => $invoiceLink,
            'canNoShow'     => $noShow,
            'recurrentList' => $recurrentList,
            'bookingNotes'  => $allNotes,
        ];

        return $bookingDetails;
    }

    public function add_note($fillable, $id=-1){
        $fillable['booking_id'] = $this->id;

        $validator = Validator::make($fillable, BookingNote::rules('POST'), BookingNote::$message, BookingNote::$attributeNames);
        if ($validator->fails()){
            return array(
                'success' => false,
                'errors' => $validator->getMessageBag()->toArray()
            );
        }

        try {
            if ($id==-1){
                $the_note = BookingNote::create($fillable);
                return ['note_id' => $the_note->id, 'success'=>true, 'message' => 'Note Created'];
            }
            else{
                $the_note = BookingNote::where('id','=',$id)->where('booking_id','=',$this->id)->get()->first();
                if ($the_note) {
                    $the_note->note_body = $fillable['note_body'];
                    $the_note->note_title = $fillable['note_title'];
                    $the_note->note_privacy = $fillable['note_privacy'];
                    $the_note->save();

                    return ['note_id' => $the_note->id, 'success'=>true, 'message' => 'Note Updated'];
                }
                else{
                    return ['success' => false, 'errors' => 'Note not found'];
                }
            }
        } catch (Exception $e) {
            return ['success' => false, 'errors' => 'Booking Error'];
        }
    }

    public function add_invoice(){
        // get last invoice number
        $invoice_number = BookingInvoice::next_invoice_number();
        $fillable = [
            'booking_id'    => $this->id,
            'invoice_number'=> $invoice_number,
            'status'        => 'pending'
        ];
        $validator = Validator::make($fillable, BookingInvoice::rules('POST'), BookingInvoice::$message, BookingInvoice::$attributeNames);
        if ($validator->fails()){
            return array(
                'success' => false,
                'errors' => $validator->getMessageBag()->toArray()
            );
        }

        usleep(1000);

        try {
            $the_invoice = BookingInvoice::create($fillable);
        }
        catch (Exception $e) {
            return ['success' => false, 'errors' => 'Booking adding invoice to selected booking'];
        }

        $loc = ShopLocations::where('id','=',$this->location_id)->get()->first();
        $location_name = $loc->name;
        $resource = ShopResource::with('vatRate')->where('id','=',$this->resource_id)->get()->first();
        $resource_name = $resource->name;
        $vat_value = $resource->vatRate->value;
        $booking_date = $this->date_of_booking;
        $booking_time_interval = $this->booking_time_start.' - '.$this->booking_time_stop;
        $booking_price = $this->payment_amount;
        $total_price = $booking_price + (($booking_price*$vat_value)/100);

        $fillable = [
            'booking_invoice_id'    => $the_invoice->id,
            'booking_id'        => $this->id,
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
        $validator = Validator::make($fillable, BookingInvoiceItem::rules('POST'), BookingInvoiceItem::$message, BookingInvoiceItem::$attributeNames);
        if ($validator->fails()){
            return array(
                'success' => false,
                'errors' => $validator->getMessageBag()->toArray()
            );
        }

        try {
            $the_invoice_item = BookingInvoiceItem::create($fillable);

            if ($the_invoice_item){
                $this->invoice_id = $the_invoice_item->id;
                $this->save();

                $the_invoice->make_general_invoice();

                return $the_invoice;
            }
            else{
                return ['success' => false, 'errors' => 'Booking Error'];
            }
        }
        catch (Exception $e) {
            return ['success' => false, 'errors' => 'Booking Error'];
        }
    }

    public function can_cancel($hours = 6){
        $bookingStartDateTime = Carbon::createFromFormat('Y-m-d H:i:s', $this->date_of_booking.' '.$this->booking_time_start);
        $can_cancel_hours = $hours;

        $user = User::where('id','=',$this->for_user_id)->get()->first();
        if (!$user){
            $can_cancel = true;
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

        return $can_cancel;
    }

    public function cancel_booking(){
        $this->status = 'canceled';
        $invoice = BookingInvoice::with('invoice_items')->where('id','=',$this->invoice_id)->get()->first();
        //xdebug_var_dump($invoice); exit;

        // check if the booking is a paid one and cancel the booking item from invoices
        if ($this->payment_type == "cash" || $this->payment_type == "recurring"){
            if ($invoice->status=='pending'){
                // no payment was done to this booking/bookings invoice
                $bookingInvoice = BookingInvoice::with('invoice_items')->where('id','=',$this->invoice_id)->get()->first();
                if (!$bookingInvoice){
                    return false;
                }

                $bookingInvoiceItem = BookingInvoiceItem::where('booking_id','=',$this->id)->where('booking_invoice_id','=',$bookingInvoice->id)->get()->first();
                if (!$bookingInvoiceItem){
                    return false;
                }

                $invoice = Invoice::with('items')->where('invoice_type','=','booking_invoice')->where('invoice_reference_id','=',$bookingInvoice->id)->get()->first();
                if (!$invoice){
                    return false;
                }

                $invoiceItem = InvoiceItem::where('item_type','=','booking_invoice_item')->where('item_reference_id','=',$bookingInvoiceItem->id)->get()->first();
                if (!$invoice){
                    return false;
                }

                // delete invoiceItem then check the invoice if it has more items; if no items found, delete empty invoice
                $invoiceItem->delete();
                if (sizeof($invoice->items)==1){
                    $invoice->status = 'cancelled';
                    $invoice->save();
                }

                // delete bookingInvoiceItem then check the bookingInvoice if it has more items; if no items found, leave the bookingInvoice empty
                $bookingInvoiceItem->delete();
                if (sizeof($bookingInvoice->invoice_items)==1){
                    $bookingInvoice->status = 'cancelled';
                    $bookingInvoice->save();
                }
            }
            elseif($invoice->status=='processing' || $invoice->status=='completed'){
                // full or partial payment done to this invoice and booking/bookings
                $bookingInvoice = BookingInvoice::with('invoice_items')->where('id','=',$this->invoice_id)->get()->first();
                if (sizeof($bookingInvoice)==0){
                    //echo '1';
                    //xdebug_var_dump($bookingInvoice); exit;
                    return false;
                }

                $bookingInvoiceItem = BookingInvoiceItem::where('booking_id','=',$this->id)->where('booking_invoice_id','=',$bookingInvoice->id)->get()->first();
                if (sizeof($bookingInvoiceItem)==0){
                    //echo '2';
                    //xdebug_var_dump($bookingInvoiceItem); exit;
                    return false;
                }

                $invoice = Invoice::with('items')->where('invoice_type','=','booking_invoice')->where('invoice_reference_id','=',$bookingInvoice->id)->get()->first();
                if (sizeof($invoice)==0){
                    //echo '3';
                    //xdebug_var_dump($invoice); exit;
                    return false;
                }

                $invoiceItem = InvoiceItem::where('item_type','=','booking_invoice_item')->where('item_reference_id','=',$bookingInvoiceItem->id)->get()->first();
                if (sizeof($invoiceItem)==0){
                    //echo $bookingInvoiceItem->id;
                    //xdebug_var_dump($invoiceItem); exit;
                    return false;
                }
//exit;
                $creditInvoice = new Invoice();
                $creditInvoice->add_credit_invoice($invoiceItem);

                // create credit invoice for canceled item if the item canceled was paid
                //$invoiceItem->delete();
                //if (sizeof($invoice->items)==1){
                //    $invoice->status = 'cancelled';
                //    $invoice->save();
                //}

                // delete bookingInvoiceItem then check the bookingInvoice if it has more items; if no items found, leave the bookingInvoice empty
                $bookingInvoiceItem->delete();
                if (sizeof($bookingInvoice->invoice_items)==1){
                    $bookingInvoice->status = 'cancelled';
                    $bookingInvoice->save();
                }
            }
        }

        if ($this->save()){
            return true;
        }
        else{
            return false;
        }
    }

    public function by_user(){
        return $this->belongsTo('App\User', 'by_user_id', 'id');
    }

    public function for_user(){
        return $this->belongsTo('App\User', 'for_user_id', 'id');
    }

    public function location(){
        return $this->belongsTo('App\ShopLocations', 'location_id', 'id');
    }

    public function resource(){
        return $this->belongsTo('App\ShopResource', 'resource_id', 'id');
    }

    public function notes(){
        return $this->hasMany('App\BookingNote', 'booking_id', 'id')->orderBy('booking_notes.created_at','asc');
    }

    public function invoice(){
        return $this->hasMany('App\BookingInvoice', 'id', 'invoice_id')->orderBy('booking_invoices.created_at','asc');
    }

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
                    'by_user_id'        => 'required|exists:users,id',
                    'for_user_id'       => 'required|exists:users,id',
                    'location_id'       => 'required|exists:shop_locations,id',
                    'resource_id'       => 'required|exists:shop_resources,id',
                    'status'            => 'required|in:pending,active,paid,canceled',
                    'date_of_booking'   => 'required|date',
                    'booking_time_start'=> 'required|date_format:"H:i"',
                    'booking_time_stop' => 'required|date_format:"H:i',
                    'custom_color_code' => 'size:7',
                    'payment_type'      => 'required|in:cash,membership,recurring',
                    'membership_id'     => '',
                    'membership_product_id' => 'exists:membership_product_id,id',
                    'invoice_id'        => '',
                    'search_key'        => 'required|unique:bookings,search_key',
                    'payment_amount'    => 'numeric',
                ];
            }
            case 'PUT':
            case 'PATCH':
            {
                return [
                    'by_user_id'        => 'required|exists:users,id',
                    'for_user_id'       => 'required|exists:users,id',
                    'location_id'       => 'required|exists:shop_locations,id',
                    'resource_id'       => 'required|exists:shop_resources,id',
                    'status'            => 'required|in:pending,active,paid,canceled',
                    'date_of_booking'   => 'required|date',
                    'booking_time_start'=> 'required|time',
                    'booking_time_stop' => 'required|time',
                    'custom_color_code' => 'size:7',
                    'payment_type'      => 'required|in:cash,membership,recurring',
                    'membership_id'     => '',
                    'membership_product_id' => 'exists:membership_product_id,id',
                    'invoice_id'        => '',
                    'search_key'        => 'required|unique:bookings,search_key'.($id ? ",$id,id" : ''),
                    'payment_amount'    => 'numeric',
                ];
            }
            default:break;
        }
    }
}