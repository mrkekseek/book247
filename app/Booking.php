<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;

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
        'location_id'   => 'Location ID',
        'resource_id'   => 'Resource ID',
        'status'        => 'Booking Status',
        'date_of_booking'   => 'Date of Booking',
        'booking_time_start'=> 'Booking Start',
        'booking_time_stop' => 'Booking End',
        'payment_type'  => 'Payment Type',
        'membership_id' => 'Membership ID',
        'invoice_id'    => 'Invoice ID',
        'search_key'    => 'Search Key'
    );

    public static $message = array();

    protected $fillable = [
        'by_user_id',
        'for_user_id',
        'location_id',
        'resource_id',
        'status',
        'date_of_booking',
        'booking_time_start',
        'booking_time_stop',
        'payment_type',
        'membership_id',
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

        if ($is_backend_employee){
            switch ($this->status) {
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

        $location = ShopLocations::find($this->location_id);
        $locationName = $location->name;
        $room = ShopResource::find($this->resource_id);
        $roomName = $room->name;
        $roomPrice= $room->session_price;
        $category = ShopResourceCategory::find($room->category_id);
        $categoryName = $category->name;

        $userBy = User::find($this->by_user_id);
        if ($userBy) {
            $madeBy = $userBy->first_name . ' ' . $userBy->middle_name . ' ' . $userBy->last_name;
        }
        $userFor= User::find($this->for_user_id);
        if ($userFor) {
            $madeFor = $userFor->first_name . ' ' . $userFor->middle_name . ' ' . $userFor->last_name;
        }

        if ($this->payment_type == 'cash'){
            $financeDetails = ' Payment of '.$this->payment_amount;
        }
        elseif($this->payment_type == 'recurring'){
            $financeDetails = "Recurrent Booking of ".$this->payment_amount;
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
            'byUserName'    => @$madeBy,
            'forUserName'   => @$madeFor,
            'forUserID'     => @$this->for_user_id,
            'canCancel'     => $canCancel,
            'canModify'     => $canModify,
            'invoiceLink'   => $invoiceLink,
            'canNoShow'     => $noShow,
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
        } catch (Exception $e) {
            return ['success' => false, 'errors' => 'Booking adding invoice to selected booking'];
        }

        $loc = ShopLocations::where('id','=',$this->location_id)->get()->first();
        $location_name = $loc->name;
        $resource = ShopResource::where('id','=',$this->resource_id)->get()->first();
        $resource_name = $resource->name;
        $booking_date = $this->date_of_booking;
        $booking_time_interval = $this->booking_time_start.' - '.$this->booking_time_stop;
        $booking_price = $this->payment_amount;
        $vat = VatRate::orderBy('id','asc')->get()->first();
        $vat_value = $vat->value;
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
        return $this->hasMany('App\BookingInvoice', 'booking_id', 'id')->orderBy('booking_invoices.created_at','asc');
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
                    'payment_type'      => 'required|in:cash,membership,recurring',
                    'membership_id'     => '',
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
                    'payment_type'      => 'required|in:cash,membership,recurring',
                    'membership_id'     => '',
                    'invoice_id'        => '',
                    'search_key'        => 'required|unique:bookings,search_key'.($id ? ",$id,id" : ''),
                    'payment_amount'    => 'numeric',
                ];
            }
            default:break;
        }
    }
}