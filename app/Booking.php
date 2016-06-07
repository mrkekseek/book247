<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;

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

        try {
            $the_invoice = BookingInvoice::create($fillable);
        } catch (Exception $e) {
            return ['success' => false, 'errors' => 'Booking Error'];
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
        } catch (Exception $e) {
            return ['success' => false, 'errors' => 'Booking Error'];
        }

        return true;
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
                    'payment_type'      => 'required|in:cash,membership',
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
                    'payment_type'      => 'required|in:cash,membership',
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
